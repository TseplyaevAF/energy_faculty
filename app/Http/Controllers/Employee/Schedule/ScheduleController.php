<?php

namespace App\Http\Controllers\Employee\Schedule;

use App\Http\Controllers\Controller;
use App\Imports\SchedulesImport;
use App\Models\Group\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function index()
    {
        $chair = auth()->user()->role_id != User::ROLE_TEACHER ?
            auth()->user()->employee->chair : auth()->user()->teacher->chair;
        $groups = Group::all();
        return view('employee.schedule.index', compact('chair', 'groups'));
    }

    public function import(Request $request) {
        Excel::import(new SchedulesImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'All good!');
    }
}
