<?php

namespace App\Http\Controllers\CertAuthority\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('ca.main.index');
    }
}
