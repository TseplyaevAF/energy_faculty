<?php

namespace App\Http\Controllers\Admin\Schedule\Chair;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Group\Group;

class ChairController extends Controller
{
    public function show(Chair $chair)
    {
        $groups = Group::all();
        return view('admin.schedule.chair.show', compact('chair', 'groups'));
    }
}
