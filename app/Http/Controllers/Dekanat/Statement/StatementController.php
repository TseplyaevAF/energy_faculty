<?php

namespace App\Http\Controllers\Dekanat\Statement;

use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Dekanat\StoreRequest;
use App\Http\Requests\Statement\FilterRequest;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Service\Dekanat\Service;
use DataTables;
use App\Exports\IndividualsExport;
use Maatwebsite\Excel\Facades\Excel;

class StatementController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(FilterRequest $request)
    {
        if ($request->ajax()) {
            $data = $request->validated();
            $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
            $data = Statement::getArrayStatements(Statement::filter($filter)->orderBy('updated_at', 'desc')->get());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'dekanat.statement.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        $groups = Group::all();
        return view('dekanat.statement.index', compact('groups'));
    }

    public function getYears($groupId)
    {
        echo(json_encode($this->getArrayYear($groupId)));
    }

    public function getDisciplines($groupId, $yearId)
    {
        echo(json_encode($this->getArrayDisciplines($groupId, $yearId)));
    }

    private function getArrayYear($groupId) {
        $lessons = Lesson::where('group_id', $groupId)->get()->unique('year_id');
        $years = [];
        foreach ($lessons as $lesson) {
            $years[] = $lesson->year;
        }
        return $years;
    }

    private function getArrayDisciplines($groupId, $yearId) {
        $lessons = Lesson::where([
            ['group_id', $groupId],
            ['year_id', $yearId]
        ])->get();
        $disciplines = [];
        foreach ($lessons as $lesson) {
            $disciplines[$lesson->id]['id'] = $lesson->id;
            $disciplines[$lesson->id]['discipline'] = $lesson->discipline->title;
            $disciplines[$lesson->id]['teacher'] = $lesson->teacher->user->surname;
            $disciplines[$lesson->id]['semester'] = $lesson->semester;
        }
        return $disciplines;
    }

    public function show(Statement $statement) {
        $individuals = Individual::getArrayCompletedSheets($statement->individuals->where('teacher_signature', '!=', ''));
        $evalTypes = Statement::getEvalTypes();
        $controlForms = Statement::getControlForms();
        return view('dekanat.statement.show',
            compact('individuals', 'evalTypes', 'statement', 'controlForms'));
    }

    public function create(Group $group) {
        $years = $this->getArrayYear($group->id);
        $controls = Statement::getControlForms();
        return view('dekanat.statement.create', compact('group', 'years', 'controls'));
    }

    public function store(StoreRequest $request) {
        $data = $request->validated();
        try {
            $this->service->signStatement($data);
        }catch (\Exception $exception) {
            return redirect()->back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('dekanat.statement.index')->withSuccess('Ведомость была успешно добавлена!');
    }

    public function export(Statement $statement) {
        try {
            $this->service->export($statement);
        } catch (\Exception $exception) {
            return redirect()->back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back()->withSuccess('Отчет сгенерирован и доступен для скачивания!');
    }

    public function download(Statement $statement) {
        return Excel::download(new IndividualsExport($statement), $statement->report);
    }
}
