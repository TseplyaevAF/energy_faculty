<?php


namespace App\Service\Group;

use App\Models\Group\Group;
use App\Models\Student\Headman;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $data += ['course' => $this->getCourse($data['semester'])];
            Group::firstOrCreate($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
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
        } catch (\Exception $exception) {
            DB::rollBack();
            $existingGroup = Group::withTrashed()->where('title', $data['title'])->first();
            if ($existingGroup->deleted_at != null) {
                throw new Exception('Группа ' . $data['title'] . ' была ранее удалена.<br/> ' . 'Не желаете восстановить?');
            } else {
                throw new Exception('Вы пытаетесь обновить существующую группу: ' . $data['title']);
            }
        }
        return $group;
    }

    private function getCourse($semester) {
        return floor(($semester+1)/2);
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
