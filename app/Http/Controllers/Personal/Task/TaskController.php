<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Category;
use App\Models\Group\Group;
use App\Models\News;
use App\Models\Teacher\Task;
use App\Service\Task\Service;
use Illuminate\Support\Facades\DB;
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
        $teacher_id = auth()->user()->role->teacher_id;
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
        // explode('/', $tasks[0]->task)[3]
        return view('personal.task.index', compact('tasks', 'groups'));
    }

    public function create()
    {
        $teacher_id = auth()->user()->role->teacher_id;
        $groupsIds = DB::table('schedules')
            ->select('group_id')
            ->groupBy('group_id')
            ->where('teacher_id', $teacher_id)
            ->get();
        $groups = [];
        foreach ($groupsIds as $groupId) {
            $groups[] = Group::find($groupId->group_id);
        }
        $disciplines = auth()->user()->role->teacher->disciplines;
        return view('personal.task.create', compact('groups', 'disciplines'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        // dd($data);

        $this->service->store(auth()->user()->role->teacher, $data);

        return redirect()->route('personal.task.index');
    }

    public function show($str) {
        dd($str);
    }

    public function download($mediaId, $filename) {
        $media = auth()->user()->role->teacher->getMedia(Task::PATH)->where('id', $mediaId)->first();

        // и сервим файл из этой медиа-модели
        return isset($media) ? response()->file($media->getPath()) : abort(404);
    }

    public function delete(News $news)
    {
        dd(1);
        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
