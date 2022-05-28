<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChairResource;
use App\Models\Chair;

class ChairController extends Controller
{
    public function index() {
        return ChairResource::collection(Chair::all());
    }
}
