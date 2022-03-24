<?php


namespace App\Service\Personal\Statement;

use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Teacher\Teacher;
use App\Service\Dekanat\DocumentSigner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    private $docSigner;

    public function __construct(DocumentSigner $docSigner)
    {
        $this->docSigner = $docSigner;
    }

    public function signIndividuals($data)
    {
        $teacher = Teacher::where('id', $data['teacher_id'])->first();
        if (!$teacher) {
            throw new \Exception('Преподаватель не найден');
        }
        try {
            $teacherCert = $this->docSigner->getCert($teacher);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        try {
            DB::beginTransaction();
            // подписать каждый индивидуальный лист студента электронной подписью
            foreach ($data['individuals'] as $individual) {
                $dataForSign = implode(",",Individual::getIndividualInfo($individual));

                $signature = $this->docSigner->getSignature($dataForSign, $data['private_key'], $teacherCert);

                $signaturePath = 'signatures/individuals/' . $individual['id'] . '/signature.dat';
                Storage::disk('public')->put($signaturePath, $signature);

                $individualEl = Individual::find($individual['id']);
                $history = json_decode($individualEl->history, true);
                $newHistoryEl = [
                    'exam_finish_date' => now(),
                    'eval' => Statement::getEvalTypes()[$individual['evaluation']],
                    'teacher' => $individualEl->statement->lesson->teacher->user->surname . ' ' .
                        mb_substr($individualEl->statement->lesson->teacher->user->name, 0, 1) . '. ' .
                        mb_substr($individualEl->statement->lesson->teacher->user->patronymic, 0, 1) . '.'
                ];
                if (!isset($history))  {
                    $history[] = $newHistoryEl;
                } else {
                    array_push($history, $newHistoryEl);
                }

                $individualEl->update([
                    'eval' => $individual['evaluation'],
                    'teacher_signature' => $signaturePath,
                    'exam_finish_date' => now(),
                    'history' => json_encode($history)
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            }
        }
    }
}
