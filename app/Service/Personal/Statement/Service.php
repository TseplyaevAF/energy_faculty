<?php


namespace App\Service\Personal\Statement;

use App\Models\Cert\Certificate;
use App\Models\Statement\Individual;
use App\Models\Teacher\Teacher;
use App\Service\Dekanat\DocumentSigner;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
                $dataForSign = json_encode(Individual::getIndividualInfo($individual));

                $signature = $this->docSigner->getSignature($dataForSign, $data['private_key'], $teacherCert);

                $signaturePath = 'signatures/individuals/' . $individual['id'] . '/signature.dat';
                Storage::disk('public')->put($signaturePath, $signature);

                Individual::find($individual['id'])->update([
                    'eval' => $individual['evaluation'],
                    'teacher_signature' => $signaturePath,
                    'exam_finish_date' => now(),
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
