<?php


namespace App\Service\Schedule;

use App\Models\Lesson;
use App\Models\Schedule\Classroom;
use App\Models\Schedule\ClassType;
use App\Models\Schedule\Schedule;
use Exception;

class Service
{
    public function store($data)
    {
        $lesson = Lesson::find($data['lesson_id']);
        $classroomInput = explode('-',$data['classroom_id']);
        $classroom = Classroom::firstOrCreate([
            'corps' => intval($classroomInput[0]),
            'cabinet' => intval($classroomInput[1]),
        ]);
        $class_type = ClassType::firstOrCreate(['title' => $data['class_type_id']]);
        $data['class_type_id'] = $class_type->id;
        $data['classroom_id'] = $classroom->id;

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
    }

    public function update($data, $schedule) {
        $lesson = Lesson::find($data['lesson_id']);

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
    }

    private function getSchedules($data) {
        $matchThese = [
            'week_type' => $data['week_type'],
            'day' => $data['day'],
            'class_time_id' => $data['class_time_id'],
        ];
        return Schedule::where($matchThese)->get();
    }

    private function groupIsBusy($data, $lesson) {
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

    private function teacherIsBusy($data, $lesson) {
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

    private function classroomIsBusy($data) {
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
