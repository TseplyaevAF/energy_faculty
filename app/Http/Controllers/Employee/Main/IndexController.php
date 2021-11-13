<?php

namespace App\Http\Controllers\Employee\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('employee.main.index');
    }
}
