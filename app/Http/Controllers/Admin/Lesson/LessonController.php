<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lesson\StoreRequest;
use App\Http\Requests\Admin\Lesson\UpdateRequest;
use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Student\Student;
use App\Models\Teacher\Teacher;
use App\Models\Year;
use App\Service\Lesson\Service;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $lessons = Lesson::all();
        return view('admin.lesson.index', compact('lessons'));
    }

    public function create()
    {
        $data = [
            'groups' => Group::all(),
            'disciplines' => Discipline::all(),
            'teachers' => Teacher::all(),
            'semesters' => range(1, 8),
            'years' => Year::all()
        ];
        return view('admin.lesson.create', compact( 'data'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->service->store($data);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.lesson.index');
    }

    public function show(Group $group)
    {
        return view('admin.group.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $students = Student::all()->where('group_id', $group->id);
        $chairs = Chair::all();
        return view('admin.group.edit', compact('group', 'students', 'chairs'));
    }

    public function update(UpdateRequest $request, Group $group)
    {
        $data = $request->validated();
        try {
            $group = $this->service->update($data, $group);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.group.show', compact('group'));
    }

    public function delete(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.group.index');
    }
}
