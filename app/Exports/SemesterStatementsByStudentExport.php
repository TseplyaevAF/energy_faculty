<?php

namespace App\Exports;

use App\Models\Statement\Statement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SemesterStatementsByStudentExport implements FromView
{
    protected $studentMarks;
    protected $statements;

    public function __construct(array $studentMarks, $statements)
    {
        $this->studentMarks = $studentMarks;
        $this->statements = $statements;
    }

    public function view(): View
    {
        return view('export.semester-statement-by-student', [
            'studentMarks' => $this->studentMarks,
            'statements' => $this->statements,
            'control_forms' => Statement::getControlForms(),
        ]);
    }
}
