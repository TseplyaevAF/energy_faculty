<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Group\Group;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        $chairs = Chair::all();
        $groups = Group::all();
        return view('admin.schedule.index', compact('chairs', 'groups'));
    }

    public function create()
    {
        
    }

    public function store()
    {
        
    }

    public function show(Chair $chair, Group $group)
    {
        dd(1);
    }

    public function edit(Schedule $schedule)
    {
        
    }

    public function update(Schedule $schedule)
    {
        
    }

    public function delete(Schedule $schedule)
    {
        
    }
}
