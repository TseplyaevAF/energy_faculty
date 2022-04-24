<?php


namespace App\Service\Task;

use App\Models\Teacher\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($data, $type)
    {
        try {
            DB::beginTransaction();
            $task = Task::create([
                'lesson_id' => $data['lesson_id'],
                'task' => 'path',
                'type' => $type
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

    // получить задания для заданной нагрузки
    public static function getTasks($lesson) {
        $arrayTasks = [];
        $tasksCount = 0;
        $arrayHomework = [];
        foreach ($lesson->tasks->where('type', Task::TEST) as $task) {
            $tasksCount++;
            $month = self::getRusMonthName(intval($task->created_at->format('m')))
                . ' ' . $task->created_at->format('Y');
            $arrayTasks[$month][$task->id] = $task->task;

            foreach ($lesson->group->students as $student) {
                $studentWork = null;
                foreach ($task->homework as $homework) {
                    if ($student->id == $homework->student_id) {
                        $studentWork = $homework;
                        break;
                    }
                }
                $studentFIO = $student->user->surname
                    . ' ' . $student->user->name
                    . ' ' . $student->user->patronymic;
                if (isset($studentWork)) {
                    $arrayHomework[$studentFIO][$task->id] = $studentWork;
                } else {
                    $arrayHomework[$studentFIO][$task->id] = null;
                }
            }
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
