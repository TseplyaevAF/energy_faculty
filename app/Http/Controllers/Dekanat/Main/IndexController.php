<?php

namespace App\Http\Controllers\Dekanat\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('dekanat.main.index');
    }
}
