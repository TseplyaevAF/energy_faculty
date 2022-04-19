<?php

namespace App\Http\Controllers\Personal\Homework;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Homework\FeedbackRequest;
use App\Http\Requests\Personal\Homework\StoreRequest;
use App\Models\Student\Homework;
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
        Gate::authorize('isStudent');
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
        Gate::authorize('isStudent');
        return view('personal.homework.create', compact('task'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('isStudent');
        $data = $request->validated();

        $this->service->store(auth()->user()->student, $data);

        return redirect()->route('personal.homework.index');
    }

    public function download($homeworkId, $mediaId, $filename) {
        $homework = Homework::find($homeworkId);
        Gate::authorize('download-homework', [$homework]);
        $media = $homework->getMedia(Homework::PATH)->where('id', $mediaId)->first();
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
        return response('Работа успешно проверена!', 200);
    }

    public function destroy(Homework $homework)
    {
        Gate::authorize('isStudent');
        $homework->delete();
        return redirect()->route('personal.homework.index');
    }
}
