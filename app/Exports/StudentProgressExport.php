<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentProgressExport implements FromView
{
    protected $studentProgress;
    protected $studentFIO;
    protected $month;
    protected $semester;

    public function __construct(array $studentProgress, $studentFIO, $month, $semester)
    {
        $this->studentProgress = $studentProgress;
        $this->studentFIO = $studentFIO;
        $this->month = $month;
        $this->semester = $semester;
    }

    public function view(): View
    {
        return view('export.student-progress', [
            'studentProgress' => $this->studentProgress,
            'studentFIO' => $this->studentFIO,
            'month' => $this->month,
            'semester' => $this->semester,
        ]);
    }
}
