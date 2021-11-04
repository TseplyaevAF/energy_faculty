<?php


namespace App\Service\User\Student;

use App\Models\Student\Student;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $array = [
                'student_id_number' => $data['student_id_number'],
                'group_id' => $data['group_id'],
            ];
            $student = Student::firstOrCreate($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $student;
    }

    public function update($data, $student)
    {
        try {
            DB::beginTransaction();
            $array = [
                'student_id_number' => $data['student_id_number'],
                'group_id' => $data['group_id'],
            ];
            $student->update($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
