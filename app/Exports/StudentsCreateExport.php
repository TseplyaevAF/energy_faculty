<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class StudentsCreateExport implements FromView, WithStrictNullComparison
{
    public function view(): View
    {
        return view('export.students-create');
    }
}
