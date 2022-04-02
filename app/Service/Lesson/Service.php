<?php


namespace App\Service\Lesson;

use App\Models\Group\Group;
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
            $teacher = Teacher::find($data['teacher_id']);
            $yearInput = explode('-',$data['year']);
            $year = Year::firstOrCreate([
                'start_year' => $yearInput[0],
                'end_year' => $yearInput[1],
            ]);
            $teacher->disciplines()->attach($data['disciplines_ids'], [
                'group_id' => $data['group_id'],
                'semester' => $data['semester'],
                'year_id' => $year->id
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function update($data, $group) {
        try {
            DB::beginTransaction();
            $data += ['semester' => $this->getSemester($data['title'])];
            $data += ['course' => $this->getCourse($data['semester'])];
            if (!empty($data['student_id'])) {
                $this->setHeadman($data['student_id'], $group);
            } else {
                if (!empty($group->headman)) {
                    $group->headman->delete();
                }
            }
            unset($data['student_id']);
            $group->update($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
