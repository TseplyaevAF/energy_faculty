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
        $teacher_id = auth()->user()->teacher->id;
        $tasks = Task::all()->where('teacher_id', $teacher_id);
        $groupsIds = DB::table('schedules')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('teacher_id', $teacher_id)
            ->get();
        $groups = [];
        foreach ($groupsIds as $groupId) {
            $groups[] = Group::find($groupId->group_id);
        }
        $disciplines = auth()->user()->teacher->disciplines;
        return view('personal.task.index', compact('tasks', 'groups', 'disciplines'));
    }

    public function create()
    {
        Gate::authorize('index-task');
        $teacher_id = auth()->user()->teacher->id;
        $groupsIds = DB::table('schedules')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('teacher_id', $teacher_id)
            ->get();
        $groups = [];
        foreach ($groupsIds as $groupId) {
            $groups[] = Group::find($groupId->group_id);
        }
        $disciplines = auth()->user()->teacher->disciplines;
        return view('personal.task.create', compact('groups', 'disciplines'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('index-task');
        $data = $request->validated();

        $this->service->store(auth()->user()->teacher, $data);

        return redirect()->route('personal.task.index');
    }

    public function download($teacherId, $mediaId, $filename) {
        Gate::authorize('download-task', [$mediaId]);
        $teacher = Teacher::find($teacherId);
        $media = $teacher->getMedia(Task::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
            ]) : abort(404);
    }

    public function show(Task $task) {
        Gate::authorize('show-task', [$task]);
        $homework = Homework::all()->where('task_id', $task->id);
        return view('personal.task.show', compact('task','homework'));
    }

    public function complete(Task $task) {
        Gate::authorize('show-task', [$task]);
        $task->update([
            'status' => 1,
        ]);
        return redirect()->route('personal.task.index');
    }
}
