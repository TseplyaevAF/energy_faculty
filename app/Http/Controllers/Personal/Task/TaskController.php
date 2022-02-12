<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Models\Teacher\Teacher;
use App\Models\Teacher\TeacherGroupDiscipline;
use App\Service\Task\Service;
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
        $tasks = Task::all()->where('teacher_id', $teacher->id);

        $groups = [];
        foreach ($teacher->group_disciplines->groupBy('group_id') as $group_id => $item) {
            $groups[] = Group::find($group_id);
        }

        $disciplines = [];
        foreach ($teacher->group_disciplines->groupBy('discipline_id') as $discipline_id => $item) {
            $disciplines[] = Discipline::find($discipline_id);
        }

        return view('personal.task.index', compact('tasks', 'groups', 'disciplines'));
    }

    public function create()
    {
        Gate::authorize('index-task');
//        $teacher = auth()->user()->teacher;
//
//        $groups = [];
//        foreach ($teacher->group_disciplines->groupBy('group_id') as $group_id => $item) {
//            $groups[] = Group::find($group_id);
//        }
//
//        $disciplines = [];
//        foreach ($teacher->group_disciplines->groupBy('discipline_id') as $discipline_id => $item) {
//            $disciplines[] = Discipline::find($discipline_id);
//        }
        $teacher = auth()->user()->teacher;
        $groupDisciplines = $teacher->group_disciplines;
        $teacherLoad = [];
        foreach ($groupDisciplines as $groupDiscipline) {
            $item['discipline'] = Discipline::where('id', $groupDiscipline->discipline_id)->first();
            $item['group'] = Group::where('id', $groupDiscipline->group_id)->first();
            $teacherLoad[$groupDiscipline->id] = $item;
            $item = [];
        }
        return view('personal.task.create', compact('teacherLoad'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('index-task');
        $data = $request->validated();

        $this->service->store(auth()->user()->teacher, $data);

        return redirect()->route('personal.task.index');
    }

    public function download($teacherId, $mediaId, $filename)
    {
        Gate::authorize('download-task', [$mediaId]);
        $teacher = Teacher::find($teacherId);
        $media = $teacher->getMedia(Task::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
        ]) : abort(404);
    }

    public function show(Task $task)
    {
        Gate::authorize('show-task', [$task]);
        $homework = Homework::all()->where('task_id', $task->id);
        return view('personal.task.show', compact('task', 'homework'));
    }

    public function complete(Task $task)
    {
        Gate::authorize('show-task', [$task]);
        $task->update([
            'status' => 1,
        ]);
        return redirect()->route('personal.task.index');
    }
}
