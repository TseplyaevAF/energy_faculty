<?php


namespace App\Service\Dekanat;

use App\Models\Cert\Certificate;
use App\Models\ExamSheet;
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
    public function signStatement($data)
    {
        $centreAuth = new CentreAuthority();
        try {
            $dekanCert = self::checkDekanCert($centreAuth);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        try {
            DB::beginTransaction();
            $statement = Statement::firstOrCreate([
                'control_form' => $data['control_form'],
                'lesson_id' => $data['lesson_id'],
                'start_date' => $data['start_date'],
                'finish_date' => $data['finish_date'],
                'dekan_signature' => '1'
            ]);

            $lesson = Lesson::find($data['lesson_id'])->first();
            $students = $lesson->group->students;

            // 4. создание индивидуальных экзаменационных листов для каждого студента
            foreach ($students as $student) {
                Individual::firstOrCreate([
                    'student_id' => $student->id,
                    'statement_id' => $statement->id
                ]);
            }

            // 5. формирование данных для подписи
            $controlForms = Statement::getControlForms();
            $dataForSignature = [
                'Экзаменационная ведомость №: ' => $statement->id,
                'Учебная группа: ' => $lesson->group->title . ', ' . $lesson->semester . ' семестр, ' .
                    $lesson->year->start_year . '-' . $lesson->year->end_year,
                'Форма контроля: ' => $controlForms[$statement->control_form]
            ];
            $dataForSignature = json_encode($dataForSignature);

            // 6. подписать ведомость электронной подписью декана
            $file = $data['private_key']->openFile();
            $dekanPrivateKey = $file->fread($file->getSize());
            try {
                $signature = $centreAuth->getSignature($dataForSignature, $dekanPrivateKey);
            } catch (\Exception $exception) {
                throw new \Exception('Секретный ключ некорректный!', -2);
            }

            // 7. проверка, что подпись декана корректна для данных data и открытого ключа public_key
            $publicKey = Storage::disk('public')->get(json_decode($dekanCert->public_key_path));
            $res = $centreAuth->checkSignature($dataForSignature, $signature, $publicKey);
            if ($res === 0 || $res !== 1) {
                throw new \Exception('Секретный ключ не соответствует публичному ключу! Подписать невозможно.',-1);
            }

            $signaturePath = 'signatures/statements/' . $statement->id . '/signature.dat';
            Storage::disk('public')->put($signaturePath, $signature);

            $statement->update([
                'dekan_signature' => $signaturePath
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    public function issueSheet($data) {
        $centreAuth = new CentreAuthority();
        $sheet = $data['sheet'];
        try {
            $dekanCert = self::checkDekanCert($centreAuth);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        try {
            DB::beginTransaction();

            // 5. формирование данных для подписи
            $dataForSignature = ExamSheet::getExamSheetInfo($sheet);

            $dataForSignature = json_encode($dataForSignature);

            // 6. подписать ведомость электронной подписью декана
            $file = $data['private_key']->openFile();
            $dekanPrivateKey = $file->fread($file->getSize());
            try {
                $signature = $centreAuth->getSignature($dataForSignature, $dekanPrivateKey);
            } catch (\Exception $exception) {
                throw new \Exception('Секретный ключ некорректный!', -2);
            }

            // 7. проверка, что подпись декана корректна для данных data и открытого ключа public_key
            $publicKey = Storage::disk('public')->get(json_decode($dekanCert->public_key_path));
            $res = $centreAuth->checkSignature($dataForSignature, $signature, $publicKey);
            if ($res === 0 || $res !== 1) {
                throw new \Exception('Секретный ключ не соответствует публичному ключу! Подписать невозможно.',-1);
            }

            $signaturePath = 'signatures/sheets/' . $sheet->id . '/signature.dat';
            Storage::disk('public')->put($signaturePath, $signature);

            // генерируем, до какого числа будет дейстовать допуск (максимум 3 дня)
            for($i=1;$i<=3;$i++){
                $before_date =  date('Y-m-d', time()+$i*24*60*60);
            }

            $sheet->update([
                'before' => $before_date,
                'dekan_signature' => $signaturePath
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($exception->getCode() == -1 || $exception->getCode() == -2) {
                throw new \Exception($exception->getMessage());
            }
        }
    }

    // проверка наличия и срока действия сертификата декана
    private function checkDekanCert($centreAuth) {
        // 1. поиск декана факультета для того,
        // чтобы можно было воспользоваться его подписью
        $dekan = Teacher::where('post', 'декан')->first();
        if (!$dekan) {
            throw new \Exception('Декан факультета не найден');
        }

        // 2. поиск сертификата декана факультета для того,
        // чтобы можно было подписать ведомость
        $dekanCert = Certificate::where('teacher_id', $dekan->id)->first();
        try {
            $cert = Storage::disk('public')->get(json_decode($dekanCert->cert_path));
        } catch (FileNotFoundException $e) {
            throw new \Exception('Сертификат не найден! Пожалуйста, обратитесь к администратору.');
        }

        // 3. проверка на срок действия сертификата
        if (!$centreAuth->checkDateValidCaCert($cert)) {
            throw new \Exception('Действие сертификата окончено! Пожалуйста, обратитесь в УЦ ЭФ, чтобы
            перевыпустить сертификат.');
        }
        return $dekanCert;
    }
}
