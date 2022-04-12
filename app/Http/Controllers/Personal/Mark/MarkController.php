<?php

namespace App\Http\Controllers\Personal\Mark;

use App\Exports\IndividualsExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
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

    public function getStatementInfo(Statement $statement) {
        if (!isset($statement->report)) {
            return response()->noContent();
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

    public function getSemesters(Group $group, $semester) {
        $data = [
            'group' => $group->id,
            'semester' => $semester,
        ];
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        $statements = Statement::filter($filter)->orderBy('updated_at', 'desc')->get()->where('report', '!=', null);
        $studentsMarks = [];
        $students = $group->students;
        $count = 0;
        while ($count != count($students)) {
            $tempArray = [];
            foreach ($statements as $statement) {
                foreach ($statement->individuals as $individual) {
                    if ($individual->student_id === $students[$count]->id) {
                        $tempStudentId = $individual;
                        break;
                    }
                }
                $tempArray[$statement->id] = Statement::getEvalTypes()[$tempStudentId->eval];
            }
            $studentsMarks[$students[$count]->user->surname
            . ' ' . $students[$count]->user->name
            . ' ' . $students[$count]->user->patronymic] = $tempArray;
            $count++;
        }
        return view('personal.mark.semester.show', compact('statements', 'studentsMarks'));
    }
}
