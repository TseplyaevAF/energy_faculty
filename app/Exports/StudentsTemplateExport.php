<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class StudentsTemplateExport implements FromView, WithStrictNullComparison
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function view(): View
    {
        return view('export.students-template', [
            'students' => $this->students,
        ]);
    }
}
