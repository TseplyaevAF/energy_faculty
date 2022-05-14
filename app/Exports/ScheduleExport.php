<?php

namespace App\Exports;

use App\Models\Schedule\ClassTime;
use App\Models\Schedule\Schedule;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ScheduleExport implements FromView, WithStrictNullComparison
{
    public function view(): View
    {
        $days = Schedule::getDays();
        $class_times = ClassTime::all();
        return view('export.schedule-template', [
            'days' => $days,
            'class_times' => $class_times,
        ]);
    }
}
