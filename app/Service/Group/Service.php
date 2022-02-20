<?php


namespace App\Service\Group;

use App\Models\Group\Group;
use App\Models\Student\Headman;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    CONST INVALID_NAME = 'not correct name';

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $data += ['semester' => $this->getSemester($data['title'])];
            $data += ['course' => $this->getCourse($data['semester'])];
            Group::firstOrCreate($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $exception = json_decode($exception->getMessage());
            if (isset($exception->code) && $exception->code === self::INVALID_NAME) {
                throw new Exception($exception->message);
            }
            $existingGroup = Group::withTrashed()->where('title', $data['title'])->first();
            if ($existingGroup->deleted_at != null) {
                throw new Exception('Группа ' . $data['title'] . ' была ранее удалена.<br/> ' . 'Не желаете восстановить?');
            } else {
                throw new Exception('Вы пытаетесь добавить существующую группу: ' . $data['title']);
            }
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
            $exception = json_decode($exception->getMessage());
            if (isset($exception->code) && $exception->code === self::INVALID_NAME) {
                throw new Exception($exception->message);
            }
            $existingGroup = Group::withTrashed()->where('title', $data['title'])->first();
            if ($existingGroup->deleted_at != null) {
                throw new Exception('Группа ' . $data['title'] . ' была ранее удалена.<br/> ' . 'Не желаете восстановить?');
            } else {
                throw new Exception('Вы пытаетесь обновить существующую группу: ' . $data['title']);
            }
        }
        return $group;
    }


    private function getCoursesArray() {
        return [
            1 => [1, 2],
            2 => [3, 4],
            3 => [5, 6],
            4 => [7, 8],
        ];
    }

    private function getSemester($groupName) {
        try {
            $groupName = explode('-', $groupName);
            if (count($groupName) === 1) {
                throw new Exception();
            }
            $year = '20' . $groupName[1];
            $year = ctype_digit($year) ? intval($year) : null;
            if ($year === null) {
                throw new Exception();
            }
            $course = intval(date("Y")) - $year;
            $result = isset($this->getCoursesArray()[$course]) ? $this->getCoursesArray()[$course] : null;
            if (in_array( intval(date("m")), range(9, 12)) || intval(date("m")) === 1) {
                return $result[0];
            } else return $result[1];
        } catch (Exception $exception) {
            $response = array('message' => 'Некорректное название группы', 'code' => self::INVALID_NAME);
            throw new Exception(json_encode($response));
        }
    }

    private function getCourse($semester) {
        return intval(floor(($semester+1)/2));
    }

    private function setHeadman($student_id, $group) {
        $headman = $group->headman;
        if (empty($headman)) {
            Headman::firstOrCreate([
                    'group_id' => $group->id,
                    'student_id' => $student_id,
            ]);
        } else {
            $headman->update([
                'student_id' => $student_id,
            ]);
        }
    }
}
