<?php

namespace App\Http\Controllers\Personal\Mark;

use App\Exports\IndividualsExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Statement\FilterRequest;
use App\Http\Resources\StatementResource;
use App\Models\Group\Group;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use Maatwebsite\Excel\Facades\Excel;

class MarkController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        return view('personal.mark.index', compact('groups'));
    }

    public function getControlForms() {
        echo json_encode(Statement::getControlForms());
    }

    public function getDisciplines(Group $group) {
        $disciplines = [];
        $lessons = $group->lessons->unique('discipline_id');
        foreach ($lessons as $lesson) {
            $disciplines[] = $lesson->discipline;
        }
        echo json_encode($disciplines);
    }

    public function getStatements(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        echo json_encode(
            StatementResource::collection(Statement::filter($filter)->orderBy('updated_at', 'desc')->get())
        );
    }

    public function getStatementInfo(Statement $statement) {
        if (!isset($statement->report)) {
            return response('Отчет еще не готов', 404);
        }
        $individuals = Individual::getArrayCompletedSheets($statement->individuals);
        $evalTypes = Statement::getEvalTypes();
        $controlForms = Statement::getControlForms();
        return view('personal.mark.statement.show',
            compact('individuals', 'evalTypes', 'statement', 'controlForms'));
    }

    public function statementDownload(Statement $statement) {
        return Excel::download(new IndividualsExport($statement), $statement->report);
    }
}
