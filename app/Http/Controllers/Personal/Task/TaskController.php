<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Group\Group;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Models\Teacher\Teacher;
use App\Service\Task\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;

class TaskController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        Gate::authorize('index-task');
        $teacher = auth()->user()->teacher;
        $disciplines = $teacher->disciplines->unique('id');
        $tasks = [];
        foreach ($teacher->lessons as $lesson) {
            foreach ($lesson->tasks as $task) {
                $tasks[] = $task;
            }
        }
        $groups = $teacher->groups->unique('id');

        return view('personal.task.index', compact('tasks', 'groups', 'disciplines'));
    }

    public function create()
    {
        Gate::authorize('index-task');
        $teacher = auth()->user()->teacher;
        foreach ($teacher->lessons as  $lesson) {
            $lessons[$lesson->id]['discipline']['id'] = $lesson->discipline->id;
            $lessons[$lesson->id]['discipline']['title'] = $lesson->discipline->title;

            $lessons[$lesson->id]['group']['id'] = $lesson->group->id;
            $lessons[$lesson->id]['group']['title'] = $lesson->group->title;

            $lessons[$lesson->id]['semester'] = $lesson->semester;
        }

        return view('personal.task.create', compact('lessons'));
    }


    public function store(StoreRequest $request)
    {
        Gate::authorize('index-task');
        $data = $request->validated();

        $this->service->store($data);

        return redirect()->route('personal.task.index');
    }

    public function download($taskId, $mediaId, $filename) {
        $task = Task::find($taskId);
        Gate::authorize('download-task', [$task]);
        $media = $task->getMedia(Task::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
            ]) : abort(404);
    }

    public function show(Task $task) {
        Gate::authorize('show-task', [$task]);
        $homework = Homework::all()->where('task_id', $task->id);
        $taskUrl = $task->getMedia(Task::PATH)->first()->getUrl();
        return view('personal.task.show', compact('task', 'taskUrl', 'homework'));
    }

    public function complete(Task $task) {
        Gate::authorize('show-task', [$task]);
        $task->update([
            'status' => 1,
        ]);
        return redirect()->route('personal.task.index');
    }
}
