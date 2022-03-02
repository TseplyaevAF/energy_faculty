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
use Illuminate\Support\Facades\Gate;

class HomeworkController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        Gate::authorize('index-homework');
        $group = auth()->user()->student->group;
        $statusVariants = Task::getStatusVariants();
        $disciplines = $group->disciplines->unique('id');
        $tasks = [];
        foreach ($group->lessons as $lesson) {
            foreach ($lesson->tasks as $task) {
                $tasks[] = $task;
            }
        }
        $homework = Homework::all()->where('student_id', auth()->user()->student->id);
        return view('personal.homework.index', compact('tasks', 'homework', 'disciplines', 'statusVariants'));
    }

    public function create(Task $task)
    {
        Gate::authorize('index-homework');
        return view('personal.homework.create', compact('task'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('index-homework');
        $data = $request->validated();

        $this->service->store(auth()->user()->student, $data);

        return redirect()->route('personal.homework.index');
    }

    public function download($studentId, $mediaId, $filename) {
        Gate::authorize('download-homework', [$mediaId]);
        $student = Student::find($studentId);
        $media = $student->getMedia(Homework::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
            ]) : abort(404);
    }

    public function feedback(FeedbackRequest $request, Homework $homework) {
        Gate::authorize('feedback-homework', [$homework]);
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

    public function delete(Homework $homework)
    {
        $homework->delete();
        return redirect()->route('personal.homework.index');
    }
}
