<?php


namespace App\Service\Group;

use App\Models\Group\Group;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    CONST INVALID_NAME = 'not correct name';

    public function store($data)
    {
        try {
            DB::beginTransaction();
            $data += ['start_year' => $this->getStartYear($data['title'])];
            $group = Group::firstOrCreate($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $exception = json_decode($exception->getMessage());
            if (isset($exception->code) && $exception->code === self::INVALID_NAME) {
                throw new Exception($exception->message);
            }
        }
        return $group;
    }

    public function update($data, $group) {
        try {
            DB::beginTransaction();
            self::setNewHeadman($group, $data['student_id']);
            unset($data['student_id']);
            $group->update($data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            $exception = json_decode($exception->getMessage());
            if (isset($exception->code) && $exception->code === self::INVALID_NAME) {
                throw new Exception($exception->message);
            }
        }
        return $group;
    }


    public static function setNewHeadman($group, $studentId) {
        if (!empty($studentId)) {
            self::setHeadman($studentId, $group);
        } else {
            if (!empty($group->getHeadman())) {
                $group->headman = null;
            }
        }
    }

    private function getStartYear($groupName) {
        try {
            $groupName = explode('-', $groupName);
            if (count($groupName) === 1) {
                throw new Exception();
            }
            $year = '20' . $groupName[1];
            $year = ctype_digit($year) ? intval($year) : null;
            if ($year === null) {
                throw new Exception();
            } else {
                return $year;
            }
        } catch (Exception $exception) {
            $response = array('message' => 'Некорректное название группы', 'code' => self::INVALID_NAME);
            throw new Exception(json_encode($response));
        }
    }

    private static function setHeadman($student_id, $group) {
        $group->headman = $group->students->where('id', $student_id)->first()->id;
    }
}
