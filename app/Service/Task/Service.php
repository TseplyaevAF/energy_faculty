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
            $task = Task::create([
                'lesson_id' => $data['lesson_id'],
                'task' => 'path'
            ]);
            $task->addMedia($data['task'])->toMediaCollection(Task::PATH);
            $task->update([
                'task' => $task->getMedia(Task::PATH)->first()->getUrl()
            ]);
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
