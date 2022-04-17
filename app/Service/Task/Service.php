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

    public static function getTasks($lessons) {
        $arrayTasks = [];
        $tasksCount = 0;
        $arrayHomework = [];
        foreach ($lessons as $lesson) {
            $tempTaskArray = [];
            foreach ($lesson->tasks as $task) {
                $tasksCount++;
                $month = self::getRusMonthName(intval($task->created_at->format('m')))
                    . ' ' . $task->created_at->format('Y');
                $tempTaskArray[$month][$task->id] = $task->task;

                foreach ($lesson->group->students as $student) {
                    $studentWork = null;
                    foreach ($task->homework as $homework) {
                        if ($student->id == $homework->student_id) {
                            $studentWork = $homework->grade;
                            break;
                        }
                    }
                    $arrayHomework[$student->user->surname
                    . ' ' . $student->user->name
                    . ' ' . $student->user->patronymic][$task->id] = $studentWork;
                }
            }
            $arrayTasks[$lesson->year->start_year . '-' . $lesson->year->end_year] = $tempTaskArray;
        }
        return [
            'arrayTasks' => $arrayTasks,
            'arrayHomework' => $arrayHomework,
            'tasksCount' => $tasksCount
        ];
    }

    private static function getRusMonthName($n)
    {
        $rusMonthNames = [
            1 => 'Январь', 'Февраль', 'Март', 'Апрель',
            'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
            'Октябрь', 'Ноябрь', 'Декабрь'
        ];
        return $rusMonthNames[$n];
    }

    public function update($data, $news)
    {

    }
}
