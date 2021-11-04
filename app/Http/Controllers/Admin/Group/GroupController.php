<?php

namespace App\Http\Controllers\Admin\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Group\StoreRequest;
use App\Http\Requests\Admin\Group\UpdateRequest;
use App\Models\Chair;
use App\Models\Group\Group;
use App\Models\Student\Student;
use App\Service\Group\Service;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $groups = DB::table('groups')->orderBy('updated_at', 'desc')->where('deleted_at', null)->get();
        return view('admin.group.index', compact('groups'));
    }

    public function create()
    {
        $students = Student::all();
        $chairs = Chair::all();
        return view('admin.group.create', compact('students', 'chairs'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->service->store($data);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.group.index');
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
