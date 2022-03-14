<?php

namespace App\Http\Controllers\Personal\Statement;

use App\Http\Controllers\Controller;
use App\Models\Year;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Service\Personal\Statement\Service;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class StatementController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $teacher = auth()->user()->teacher;
        if ($request->ajax()) {
            $statements = [];
            $lessons = null;
            if (!empty($request->filter_group) && empty($request->filter_year)) {
                $lessons = $teacher->lessons->where('group_id', $request->filter_group);
            }
            if (!empty($request->filter_year)) {
                $lessons = $teacher->lessons->where('group_id', $request->filter_group)
                    ->where('year_id', $request->filter_year);
            }
            if (isset($lessons)) {
                foreach ($lessons as $lesson) {
                    foreach ($lesson->statements as $statement) {
                        $statements[] = $statement;
                    }
                }
            } else {
                foreach ($teacher->lessons as $lesson) {
                    foreach ($lesson->statements as $statement) {
                        $statements[] = $statement;
                    }
                }
            }
            $data = Statement::getArrayStatements($statements);
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
            'individuals' => $individuals,
            'private_key' => $private_key
        ];
        try {
            $this->service->signData($data);
            return response('Ведомость успешно подписана!', 200);
        }catch (\Exception $exception) {
            return response($exception->getMessage(), 403);
        }
    }
}
