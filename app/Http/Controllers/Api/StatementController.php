<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Statement\FilterRequest;
use App\Http\Resources\StatementResource;
use App\Models\Statement\Statement;

class StatementController extends Controller
{
    public function index(FilterRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        return StatementResource::collection(Statement::filter($filter)->orderBy('updated_at', 'desc')->get());
    }

    public function getControlForms() {
        return Statement::getControlForms();
    }
}
