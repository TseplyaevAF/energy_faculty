<?php

namespace App\Http\Controllers\Personal\Homework;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Homework\FeedbackRequest;
use App\Http\Requests\Personal\Homework\StoreRequest;
use App\Models\Discipline;
use App\Models\News;
use App\Models\Student\Homework;
use App\Models\Student\Student;
use App\Models\Teacher\Task;
use App\Service\Homework\Service;
use Illuminate\Support\Facades\DB;

class HomeworkController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        $statusVariants = Task::getStatusVariants();
        $student = auth()->user()->student;
        $homework = Homework::all()->where('student_id', $student->id);
        $tasks = Task::all()->where('group_id', $student->group_id);
        $disciplinesIds = DB::table('schedules')
            ->select('discipline_id')
            ->groupBy('discipline_id')
            ->where('group_id', $student->group_id)
            ->get();
        $disciplines = [];
        foreach ($disciplinesIds as $disciplineId) {
            $disciplines[] = Discipline::find($disciplineId->discipline_id);
        }
        return view('personal.homework.index', compact('tasks', 'homework', 'disciplines', 'statusVariants'));
    }

    public function create(Task $task)
    {
        return view('personal.homework.create', compact('task'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->service->store(auth()->user()->student, $data);

        return redirect()->route('personal.homework.index');
    }

    public function download($studentId, $mediaId, $filename) {
        $student = Student::find($studentId);
        $media = $student->getMedia(Homework::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath()) : abort(404);
    }

    public function show(Homework $homework) {
        dd($homework);
    }

    public function feedback(FeedbackRequest $request, Homework $homework) {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $homework->update([
                'grade' => $data['grade']
            ]);
            $task = Task::find($data['task_id']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return redirect()->route('personal.task.show', compact('task'));
    }

    public function delete(News $news)
    {
        dd(1);
        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
