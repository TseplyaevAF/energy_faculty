<?php


namespace App\Service\Task;

use App\Models\Teacher\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $task = Task::Create([
                'lesson_id' => $data['lesson_id'],
                'task' => 'path'
            ]);
            $task->addMedia($data['task'])->toMediaCollection(Task::PATH);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $news)
    {

    }
}
