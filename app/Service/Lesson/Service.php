<?php


namespace App\Service\Lesson;

use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Teacher\Teacher;
use App\Models\Year;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            Lesson::firstOrCreate([
                'group_id' => $data['group_id'],
                'semester' => $data['semester'],
                'year_id' => $data['year_id'],
                'teacher_id' => $data['teacher_id'],
                'discipline_id' => $data['discipline_id'],
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    public function update($data, $lesson) {
        try {
            DB::beginTransaction();
            $lesson->update($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
