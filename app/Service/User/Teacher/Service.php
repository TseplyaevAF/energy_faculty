<?php


namespace App\Service\User\Teacher;

use App\Models\Teacher\Teacher;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $array = [
                'post' => $data['post'],
                'activity' => $data['activity'],
                'work_experience' => $data['work_experience'],
                'address' => $data['address'],
                'chair_id' => $data['chair_id'],
                'user_id' => $data['user_id'],
            ];
            $teacher = Teacher::firstOrCreate($array);
            if (isset($data['disciplines_ids'])) {
                $teacher->disciplines()->attach($data['disciplines_ids']);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $teacher;
    }

    public function update($data, $teacher)
    {
        try {
            DB::beginTransaction();
            $array = [
                'post' => $data['post'],
                'activity' => $data['activity'],
                'work_experience' => $data['work_experience'],
                'address' => $data['address'],
                'chair_id' => $data['chair_id'],
            ];
            $teacher->update($array);
            if (isset($data['disciplines_ids'])) {
                $teacher->disciplines()->sync($data['disciplines_ids']);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $teacher;
    }
}
