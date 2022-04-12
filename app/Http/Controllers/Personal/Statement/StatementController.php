<?php

namespace App\Http\Controllers\Personal\Statement;

use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Statement\FilterRequest;
use App\Models\Year;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Service\Personal\Statement\Service;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Gate;
use Validator;

class StatementController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        if ($request->ajax()) {
            $data = $request->validated();
            $data['teacher'] = $teacher->id;
            $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
            $data = Statement::getArrayStatements(Statement::filter($filter)->orderBy('updated_at', 'desc')->get());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('show', 'personal.statement.action.show')
                ->rawColumns(['show'])
                ->make(true);
        }
        $groups = $teacher->groups->unique('id');
        return view('personal.statement.index', compact('groups'));
    }

    public function show(Request $request, Statement $statement)
    {
        Gate::authorize('isTeacher');
        $individuals = $statement->individuals;
        $evalTypes = Statement::getEvalTypes();
        $data = Individual::getArrayIndividuals($individuals);
        if ($request->ajax()) {
            return DataTables::of($data)
                ->make(true);
        }
        $controlForms = Statement::getControlForms();
        return view('personal.statement.show',
            compact('statement', 'controlForms', 'evalTypes'));
    }

    public function getYears($groupId)
    {
        echo(json_encode(Year::getArrayYearsByGroup($groupId)));
    }

    public function getCompletedSheets(Statement $statement) {
        echo(json_encode(Individual::getArrayCompletedSheets($statement->individuals->where('teacher_signature', '!=', ''))));
    }

    public function getEvalTypes()
    {
        echo(json_encode(Statement::getEvalTypes()));
    }

    public function saveData(Request $request)
    {
        Gate::authorize('isTeacher');
        $request->validate([
            'rows' => 'required|array',
            'rows.*.id' => 'required|integer|exists:individuals,id',
            'rows.*.evaluation' => 'nullable|string'
        ]);
        $individuals = $request->rows;

        foreach ($individuals as $individual) {
            Individual::where('id', $individual['id'])->
            update([
                'eval' => $individual['evaluation']
            ]);
        }
        return response('Ведомость успешно сохранена!', 200);
    }

    public function signStatement(Request $request, Statement $statement)
    {
        Gate::authorize('isTeacher');
        $individuals = json_decode($request->individuals, true);
        $rules = [
            'individuals' => 'required|array',
            'individuals.*.id' => 'required|integer|exists:individuals,id',
            'individuals.*.evaluation' => 'required|string',
        ];

        $validator = Validator::make(['individuals' => $individuals], $rules);
        if (!$validator->passes()) {
            return response('Необходимо заполнить оценки для всех студентов', 404);
        }
        try {
            $request->validate([
                'private_key' => 'required|file'
            ]);
        } catch (\Exception $exception) {
            return response('Необходимо выбрать файл', 404);
        }

        $private_key = $request->private_key;
        $data = [
            'statement' => $statement,
            'teacher_id' => auth()->user()->teacher->id,
            'individuals' => $individuals,
            'private_key' => $private_key
        ];
        try {
            $this->service->signIndividuals($data);
            return response('Ведомость успешно подписана!', 200);
        }catch (\Exception $exception) {
            return response($exception->getMessage(), 403);
        }
    }
}
