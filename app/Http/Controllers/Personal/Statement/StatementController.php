<?php

namespace App\Http\Controllers\Personal\Statement;

use App\Http\Controllers\Controller;
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
                    if (isset($lesson->statement)) {
                        $statements[] = $lesson->statement;
                    }
                }
            } else {
                foreach ($teacher->lessons as $lesson) {
                    foreach ($lesson->statements as $statement) {
                        $statements[] = $statement;
                    }
                }
            }

            $data = self::getArrayStatements($statements);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'personal.statement.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        $groups = $teacher->groups;
        return view('personal.statement.index', compact('groups'));
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
}
