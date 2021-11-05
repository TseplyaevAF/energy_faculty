<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Group\Group;

class ScheduleController extends Controller
{
    public function index()
    {
        $chairs = Chair::all();
        $groups = Group::all();
        foreach ($chairs as $chair) {
            $chairsIds[] = $chair->id;
        }
        return view('admin.schedule.index', compact('chairs', 'groups', 'chairsIds'));
    }
}
