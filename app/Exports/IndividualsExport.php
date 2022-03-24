<?php

namespace App\Exports;

use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IndividualsExport implements FromView
{
    protected $statement;

    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    public function view(): View
    {
        return view('dekanat.export.individuals', [
            'individuals' => Individual::getArrayCompletedSheets($this->statement->individuals),
            'statement' => $this->statement,
            'evalTypes' => Statement::getEvalTypes(),
            'controlForms' => Statement::getControlForms()
        ]);
    }
}
