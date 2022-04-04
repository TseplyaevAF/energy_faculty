<?php


namespace App\Service\Schedule;

use App\Models\Lesson;
use App\Models\Schedule\Classroom;
use App\Models\Schedule\ClassType;
use App\Models\Schedule\Schedule;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $lesson = Lesson::find($data['lesson_id']);
            $data['class_type_id'] = $this->getClassType($data['class_type_id'])->id;
            $data['classroom_id'] = $this->getClassroom($data['classroom_id'])->id;

            $result = $this->groupIsBusy($data, $lesson);
            if (!empty($result)) {
                throw new Exception('Группе уже назначена пара на это время');
            }

            $result = $this->teacherIsBusy($data, $lesson);
            if (!empty($result)) {
                throw new Exception('В это время преподаватель ведет пару у группы: ' . $result->lesson->group->title);
            }

            $result = $this->classroomIsBusy($data);
            if (!empty($result)) {
                throw new Exception('Аудитория занята у группы: ' . $result->lesson->group->title);
            }
            unset($data['group_id']);
            Schedule::firstOrCreate($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    public function update($data, $schedule)
    {
        try {
            DB::beginTransaction();
            $lesson = Lesson::find($data['lesson_id']);
            $data['class_type_id'] = $this->getClassType($data['class_type_id'])->id;
            $data['classroom_id'] = $this->getClassroom($data['classroom_id'])->id;

            $result = $this->groupIsBusy($data, $lesson);
            if (!empty($result) && ($result->id != $schedule->id)) {
                throw new Exception('Группе уже назначена пара на это время');
            }

            $result = $this->teacherIsBusy($data, $lesson);
            if (!empty($result) && ($result->id != $schedule->id)) {
                throw new Exception('В это время преподаватель ведет пару у группы: ' . $result->lesson->group->title);
            }

            $result = $this->classroomIsBusy($data);
            if (!empty($result) && ($result->id != $schedule->id)) {
                throw new Exception('Аудитория занята у группы: ' . $result->lesson->group->title);
            }

            unset($data['group_id']);
            $schedule->update($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }
    }

    private function getClassType($classType)
    {
        return ClassType::firstOrCreate(['title' => $classType]);
    }

    private function getClassroom($classroom)
    {
        $classroomInput = explode('-', $classroom);
        return Classroom::firstOrCreate([
            'corps' => intval($classroomInput[0]),
            'cabinet' => intval($classroomInput[1]),
        ]);
    }

    private function getSchedules($data)
    {
        $matchThese = [
            'week_type' => $data['week_type'],
            'day' => $data['day'],
            'class_time_id' => $data['class_time_id'],
        ];
        return Schedule::where($matchThese)->get();
    }

    private function groupIsBusy($data, $lesson)
    {
        $schedules = self::getSchedules($data);
        if (!isset($schedules)) {
            return false;
        } else {
            foreach ($schedules as $schedule) {
                if ($schedule->lesson->group_id == $lesson->group_id) {
                    return $schedule;
                }
            }
        }
        return false;
    }

    private function teacherIsBusy($data, $lesson)
    {
        $schedules = self::getSchedules($data);
        if (!isset($schedules)) {
            return false;
        } else {
            foreach ($schedules as $schedule) {
                if ($schedule->lesson->teacher_id == $lesson->teacher_id) {
                    return $schedule;
                }
            }
        }
        return false;
    }

    private function classroomIsBusy($data)
    {
        $matchThese = [
            'week_type' => $data['week_type'],
            'day' => $data['day'],
            'class_time_id' => $data['class_time_id'],
            'classroom_id' => $data['classroom_id'],
        ];
        $result = Schedule::where($matchThese)->first();
        return !empty($result) ? $result : false;
    }
}
