<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;

use App\Http\Requests\News\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Category;
use App\Models\Group\Group;
use App\Models\News;
use App\Models\Teacher\Task;
use App\Service\News\Service;
use Illuminate\Support\Facades\DB;

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
        dd($data);

        $this->service->store($data);

        return redirect()->route('employee.news.index');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $images = json_decode($news->images);
        return view('employee.news.edit', compact('news', 'categories', 'images'));
    }

    public function update(UpdateRequest $request, News $news)
    {
        $data = $request->validated();

        $news = $this->service->update($data, $news);

        return redirect()->back()->withSuccess('Запись успешно отредактирована');
    }

    public function delete(News $news)
    {
        dd(1);
        $news->delete();
        return redirect()->route('admin.group.news.index');
    }
}
