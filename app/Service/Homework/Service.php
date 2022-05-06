<?php


namespace App\Service\Homework;

use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($student, $data)
    {
        try {
            DB::beginTransaction();
            $homework = Homework::create([
                'task_id' => $data['task_id'],
                'student_id' => $student->id,
                'homework' => 'path'
            ]);
            $homework->addMedia($data['homework'])->toMediaCollection(Homework::PATH);
            $homework->update([
                'homework' => $homework->getMedia(Homework::PATH)->first()->getUrl()
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    // получить задания для заданной нагрузки
    public static function getTasks($lesson, $student) {
        $arrayTasks = [];
        $tasksCount = 0;
        $arrayHomework = [];
        foreach ($lesson->tasks->where('type', Task::TEST) as $task) {
            $tasksCount++;
            $month = \App\Service\Task\Service::getRusMonthName(intval($task->created_at->format('m')))
                . ' ' . $task->created_at->format('Y');
            $arrayTasks[$month][$task->id] = $task;
            $arrayHomework[$student->user->fullName()][$task->id] =
                \App\Service\Task\Service::getStudentWork($task, $student);
        }
        return [
            'arrayTasks' => $arrayTasks,
            'arrayHomework' => $arrayHomework,
            'tasksCount' => $tasksCount
        ];
    }

    public function update($data, $news)
    {

    }
}
