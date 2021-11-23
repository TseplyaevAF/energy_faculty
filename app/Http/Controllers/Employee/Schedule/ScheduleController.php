<?php

namespace App\Http\Controllers\Employee\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Group\Group;

class ScheduleController extends Controller
{
    public function index()
    {
        $chair = auth()->user()->employee->chair;
        $groups = Group::all();
        return view('employee.schedule.index', compact('chair', 'groups'));
    }
}
