<?php


namespace App\Service\Personal\Schedule;

use App\Models\Schedule;
use App\Models\Teacher\Teacher;
use Exception;

class Service
{
    public function update($data, $schedule) {
        if (!in_array($data['discipline_id'], Teacher::find($data['teacher_id'])->disciplines->pluck('id')->toArray())) {
            throw new Exception('Преподаватель не ведёт занятия по выбранной дисциплине');
        }

        if ($this->getBusyTime($data)) {
            throw new Exception('Группе уже назначена пара на это время');
        }

        $result = $this->getBusyTeacherUpdate($data);
        if (!empty($result)) {
            throw new Exception('В это время преподаватель ведет пару у группы: ' . $result->group->title);
        }

        $result = $this->getBusyTeacher($data);
        if (!empty($result) && ($schedule->id != $result->id)) {
            throw new Exception('У преподавателя другая пара: ' . $result->day->title . ', ' . $result->group->title);
        }

        $result = $this->getBusyClassroomUpdate($data);
        if (!empty($result)) {
            throw new Exception('Аудитория занята у группы: ' . $result->group->title);
        }

        $schedule->update($data);
    }
    
    private function getBusyTime($data) {
        $matchThese = [
            'week_type_id' => $data['week_type_id'],
            'day_id' => $data['day_id'],
            'class_time_id' => $data['class_time_id'],
            'group_id' => $data['group_id'],
        ];
        return !empty(Schedule::where($matchThese)->first()) ? true : false;
    }

    private function getBusyTeacher($data) {
        $matchThese = [
            'week_type_id' => $data['week_type_id'],
            'day_id' => $data['day_id'],
            'class_time_id' => $data['class_time_id'],
            'teacher_id' => $data['teacher_id'],
        ];
        $result = Schedule::where($matchThese)->first();
        return !empty($result) ? $result : false;
    }

    private function getBusyTeacherUpdate($data) {
        $matchThese = [
            'week_type_id' => $data['week_type_id'],
            'day_id' => $data['day_id'],
            'class_time_id' => $data['class_time_id'],
            'teacher_id' => $data['teacher_id'],
        ];
        $result = Schedule::where($matchThese)
        ->where('group_id', '!=', $data['group_id'])
        ->first();
        return !empty($result) ? $result : false;
    }

    private function getBusyClassroomUpdate($data) {
        $matchThese = [
            'week_type_id' => $data['week_type_id'],
            'day_id' => $data['day_id'],
            'class_time_id' => $data['class_time_id'],
            'classroom_id' => $data['classroom_id'],
        ];
        $result = Schedule::where($matchThese)
        ->where('group_id', '!=', $data['group_id'])
        ->first();
        return !empty($result) ? $result : false;
    }
}
