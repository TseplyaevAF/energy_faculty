<?php


namespace App\Service\Homework;

use App\Models\Student\Homework;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($student, $data)
    {
        try {
            DB::beginTransaction();
            $homework = Homework::create([
                'task_id' => $data['task_id'],
                'student_id' => $student->id
            ]);
            $homework->addMedia($data['homework'])->toMediaCollection(Homework::PATH);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $news)
    {

    }
}
