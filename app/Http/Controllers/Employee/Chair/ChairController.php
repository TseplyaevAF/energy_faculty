<?php

namespace App\Http\Controllers\Employee\Chair;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Chair\UpdateRequest;
use App\Models\Chair;

class ChairController extends Controller
{
    public function edit(Chair $chair)
    {
        return view('employee.chair.edit', compact('chair'));
    }

    public function update(UpdateRequest $request, Chair $chair)
    {
        $data = $request->validated();

        $chair->update($data);

        return redirect()->route('employee.chair.edit', compact('chair'));
    }
}
