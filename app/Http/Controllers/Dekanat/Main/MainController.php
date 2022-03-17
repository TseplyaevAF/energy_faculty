<?php

namespace App\Http\Controllers\Dekanat\Main;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;

class MainController extends Controller
{
    public function index()
    {
        return view('dekanat.main.index');
    }

    public function issue(ExamSheet $sheet)
    {
        
        return view('dekanat.main.index');
    }
}
