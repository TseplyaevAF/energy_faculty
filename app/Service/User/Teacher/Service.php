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
                'rank' => $data['rank'],
                'chair_id' => $data['chair_id'],
                'user_id' => $data['user_id'],
            ];
            Teacher::firstOrCreate($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $teacher)
    {
        try {
            DB::beginTransaction();
            $array = [
                'post' => $data['post'],
                'rank' => $data['rank'],
                'chair_id' => $data['chair_id'],
                'user_id' => $data['user_id'],
            ];
            $teacher->update($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
