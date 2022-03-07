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
                    if (isset($lesson->statement)) {
                        $statements[] = $lesson->statement;
                    }
                }
            } else {
                $statements = Statement::all();
            }

            $data = self::getArrayStatements($statements);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'dekanat.statement.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        $groups = Group::all();
        return view('dekanat.statement.index', compact('groups'));
    }

    private function getArrayStatements($statements)
    {
        $data = [];
        $controlForms = Statement::getControlForms();
        foreach ($statements as $statement) {
            $data[$statement->id]['id'] = $statement->id;
            $data[$statement->id]['group'] = $statement->lesson->group;
            $data[$statement->id]['year'] = $statement->lesson->year->start_year . '-' .
                $statement->lesson->year->end_year;
            $data[$statement->id]['discipline'] = $statement->lesson->discipline;
            $data[$statement->id]['control_form'] = $controlForms[$statement->control_form];
            $data[$statement->id]['semester'] = $statement->lesson->semester;
        }
        return $data;
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
