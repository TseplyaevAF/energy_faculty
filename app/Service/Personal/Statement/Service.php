<?php


namespace App\Service\Personal\Statement;

use App\Models\Cert\Certificate;
use App\Models\Lesson;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Teacher\Teacher;
use App\Service\CA\CentreAuthority;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function signData($data)
    {
        $teacher = $data['statement']->lesson->teacher;
        // 1. поиск сертификата преподавателя
        $teacherCert = Certificate::where('teacher_id', $teacher->id)->first();
        try {
            $cert = Storage::disk('public')->get(json_decode($teacherCert->cert_path));
        } catch (FileNotFoundException $e) {
            throw new \Exception('Сертификат не найден! Пожалуйста, обратитесь к администратору.');
        }

        $centreAuth = new CentreAuthority();

        // 2. проверка на срок действия сертификата
        if (!$centreAuth->checkDateValidCaCert($cert)) {
            throw new \Exception('Действие сертификата окончено! Пожалуйста, обратитесь в УЦ ЭФ, чтобы
            перевыпустить сертификат.');
        }
        try {
            DB::beginTransaction();

            // 3. подписать каждый индивидуальный лист студента
            // электронной подписью
            $file = $data['private_key']->openFile();
            $teacherPrivateKey = $file->fread($file->getSize());
            foreach ($data['individuals'] as $individual) {
                $dataForSignature = [
                    'Студент' => $individual['studentFIO'],
                    '№ студенческого билета: ' => $individual['student_id_number']
                ];
                $dataForSignature = json_encode($dataForSignature);

                try {
                    $signature = $centreAuth->getSignature($dataForSignature, $teacherPrivateKey);
                } catch (\Exception $exception) {
                    throw new \Exception('Секретный ключ некорректный!', -2);
                }

                // 4. проверка, что подпись корректна для данных data и открытого ключа public_key
                $publicKey = Storage::disk('public')->get(json_decode($teacherCert->public_key_path));
                $res = $centreAuth->checkSignature($dataForSignature, $signature, $publicKey);
                if ($res === 0 || $res !== 1) {
                    throw new \Exception('Секретный ключ не соответствует публичному ключу! Подписать невозможно.',-1);
                }

                $signaturePath = 'signatures/individuals/' . $individual['id'] . '/signature.dat';
                Storage::disk('public')->put($signaturePath, $signature);

                Individual::find($individual['id'])->update([
                    'eval' => $individual['eval'],
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
