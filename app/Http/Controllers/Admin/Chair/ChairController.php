<?php

namespace App\Http\Controllers\Admin\Chair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Chair\StoreRequest;
use App\Http\Requests\Admin\Chair\UpdateRequest;
use App\Models\Chair;

class ChairController extends Controller
{
    public function index()
    {
        $chairs = Chair::all();
        return view('admin.chair.index', compact('chairs'));
    }

    public function create()
    {
        return view('admin.chair.create');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        Chair::firstOrcreate($data);

        return redirect()->route('admin.chair.index');
    }

    public function show(Chair $chair)
    {
        return view('admin.chair.show', compact('chair'));
    }

    public function edit(Chair $chair)
    {
        return view('admin.chair.edit', compact('chair'));
    }

    public function update(UpdateRequest $request, Chair $chair)
    {
        $data = $request->validated();

        $chair->update($data);

        return redirect()->route('admin.chair.index');
    }

    public function delete(Chair $chair)
    {
        $chair->delete();
        return redirect()->route('admin.chair.index');
    }
}
