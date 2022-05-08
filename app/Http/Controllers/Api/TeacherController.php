<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\TeacherFilter;
use App\Http\Requests\Personal\Teacher\FilterRequest;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(TeacherFilter::class, ['queryParams' => array_filter($data)]);
        return TeacherResource::collection(Teacher::filter($filter)->get());
    }
}
