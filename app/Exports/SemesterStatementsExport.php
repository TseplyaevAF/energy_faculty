<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SemesterStatementsExport implements FromView
{
    protected $studentsMarks;
    protected $statements;

    public function __construct(array $studentsMarks, $statements)
    {
        $this->studentsMarks = $studentsMarks;
        $this->statements = $statements;
    }

    public function view(): View
    {
        return view('export.semester-statement', [
            'studentsMarks' => $this->studentsMarks,
            'statements' => $this->statements,
        ]);
    }
}
