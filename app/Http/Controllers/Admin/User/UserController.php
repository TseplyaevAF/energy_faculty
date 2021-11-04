<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\User;
use App\Service\User\Service;

class UserController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {   
        $roles = User::getRoles();
        $groups = Group::all();
        $chairs = Chair::all();
        $disciplines = Discipline::all();
        return view('admin.user.create', compact('roles', 'groups', 'chairs', 'disciplines'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        
        $this->service->store($data);

        return redirect()->route('admin.user.index');
    }

    public function show(User $user)
    {
        return view('admin.discipline.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role->teacher_id != null) {
            $chairs = Chair::all();
            $disciplines = Discipline::all();
            return view('admin.user.edit', compact('user', 'chairs', 'disciplines'));
        } else if ($user->role->student_id != null) {
            $groups = Group::all();
            return view('admin.user.edit', compact('user', 'groups'));
        }
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();

        $user = $this->service->update($data, $user);

        return redirect()->route('admin.user.index');
    }

    public function delete(User $user)
    {
        if ($user->role->teacher_id != null) {
            $user->role->teacher->delete();
        } else if ($user->role->student_id != null) {
            $user->role->student->delete();
        }
        $user->role->delete();
        $user->delete();
        return redirect()->route('admin.user.index');
    }
}