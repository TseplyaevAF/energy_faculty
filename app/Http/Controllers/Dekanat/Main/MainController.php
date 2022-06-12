<?php

namespace App\Http\Controllers\Dekanat\Main;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;
use App\Models\Statement\Statement;

class MainController extends Controller
{
    public function index()
    {
        $statements = Statement::with('individuals')->get();
        $statementsCount = 0;
        $examSheetsCount = ExamSheet::where('dekan_signature', null)->get()->count();
        foreach ($statements as $statement) {
            $isSigned = count($statement->individuals->where('teacher_signature', '!=', null)) != 0 ? true : false;
            if ($isSigned && $statement->report == null && $statement->finish_date < now()) {
                $statementsCount++;
            }
        }
        return view('dekanat.main.index', compact('statementsCount', 'examSheetsCount'));
    }

    public function issue(ExamSheet $sheet)
    {
        
        return view('dekanat.main.index');
    }
}
