<?php


namespace App\Service\Dekanat;

use App\Models\Lesson;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Teacher\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();

            $statement = Statement::firstOrCreate([
                'form_exam' => $data['form_exam'],
                'lesson_id' => $data['lesson_id'],
                'exam_date' => $data['exam_date'],
                'finish_date' => $data['finish_date'],
            ]);

            $dekan = Teacher::where('post', 'декан')->first();
            $publicKeyPath = 'ca/certs/teachers/' . $dekan->id . '/public_key.pem';
            $publicKey = Storage::disk('public')->get($publicKeyPath);

            $lesson = Lesson::find($data['lesson_id'])->first();
            $students = $lesson->group->students;

            foreach ($students as $student) {
                $individuals[] = Individual::firstOrCreate([
                    'student_id' => $student->id,
                    'statement_id' => $statement->id,
                    'dekan_signature' => '1'
                ]);
            }
            $file = $data['private_key']->openFile();
            $private_key = $file->fread($file->getSize());
            openssl_sign(json_encode($individuals), $signature, $private_key, OPENSSL_ALGO_SHA256);
            $res = openssl_verify(json_encode($individuals), $signature, $publicKey, "sha256WithRSAEncryption");
            if ($res === 0 || $res !== 1) {
                throw new \Exception('Сертификат недействительный!');
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
