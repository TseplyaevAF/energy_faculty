<?php

namespace App\Http\Controllers\Admin\Discipline;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discipline\StoreRequest;
use App\Http\Requests\Admin\Discipline\UpdateRequest;
use App\Models\Discipline;

class DisciplineController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::all();
        return view('admin.discipline.index', compact('disciplines'));
    }

    public function create()
    {
        return view('admin.discipline.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        Discipline::create($data);

        return redirect()->route('admin.discipline.index');
    }

    public function show(Discipline $discipline)
    {
        return view('admin.discipline.show', compact('discipline'));
    }

    public function edit(Discipline $discipline)
    {
        return view('admin.discipline.edit', compact('discipline'));
    }

    public function update(UpdateRequest $request, Discipline $discipline)
    {
        $data = $request->validated();

        $discipline->update($data);

        return redirect()->route('admin.discipline.index');
    }

    public function delete(Discipline $discipline)
    {
        $discipline->delete();
        return redirect()->route('admin.discipline.index');
    }
}
