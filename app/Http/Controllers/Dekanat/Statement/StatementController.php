<?php

namespace App\Http\Controllers\Dekanat\Statement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dekanat\StoreRequest;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Statement\Statement;
use App\Service\Dekanat\Service;
use Illuminate\Http\Request;
use DataTables;

class StatementController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $statements = [];
            $lessons = null;
            if (!empty($request->filter_group) && empty($request->filter_year)) {
                $lessons = Lesson::where('group_id', $request->filter_group)
                    ->get();
            }
            if (!empty($request->filter_year)) {
                $lessons = Lesson::where('year_id', $request->filter_year)
                    ->where('group_id', $request->filter_group)
                    ->get();
            }
            if (isset($lessons)) {
                foreach ($lessons as $lesson) {
                    foreach ($lesson->statements as $statement) {
                        $statements[] = $statement;
                    }
                }
            } else {
                $statements = Statement::all();
            }

            $data = Statement::getArrayStatements($statements);
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

    public function create(Group $group) {
        $years = $this->getArrayYear($group->id);
        $controls = Statement::getControlForms();
        return view('dekanat.statement.create', compact('group', 'years', 'controls'));
    }

    public function store(StoreRequest $request) {
        $data = $request->validated();
        try {
            $this->service->store($data);
            return redirect()->route('dekanat.statement.index')->withSuccess('Ведомость была успешно добавлена!');
        }catch (\Exception $exception) {
            return redirect()->back()->withError($exception->getMessage())->withInput();
        }
    }
}
