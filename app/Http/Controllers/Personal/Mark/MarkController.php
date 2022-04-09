<?php

namespace App\Http\Controllers\Personal\Mark;

use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Statement\FilterRequest;
use App\Http\Resources\StatementResource;
use App\Models\Group\Group;
use App\Models\Statement\Statement;

class MarkController extends Controller
{
    public function index()
    {
        return view('personal.mark.index');
    }

    public function getGroups() {
        echo json_encode(Group::all());
    }

    public function getStatements(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        echo json_encode(
            StatementResource::collection(Statement::filter($filter)->orderBy('updated_at', 'desc')->get())
        );
    }
}
