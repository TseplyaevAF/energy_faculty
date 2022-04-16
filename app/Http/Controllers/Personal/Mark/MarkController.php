<?php

namespace App\Http\Controllers\Personal\Mark;

use App\Exports\IndividualsExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\StatementFilter;
use App\Http\Resources\StudentResource;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Student\Student;
use App\Service\Group\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

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

    public function getStudents(Group $group) {
        echo json_encode(StudentResource::collection($group->students));
    }

    public function setNewHeadman(Group $group, $headmanId) {
        $student = Student::find($headmanId)->first();
        if (isset($student)) {
            try {
                DB::beginTransaction();
                Service::setNewHeadman($group, $headmanId);
                $group->update([
                    'headman' => $headmanId
                ]);
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();
                return response($exception->getMessage(), 500);
            }
        } else {
            return response('Студент не найден', 404);
        }
        return response('Назначен новый староста', 200);
    }

    public function getDisciplines(Group $group) {
        $disciplines = [];
        $lessons = $group->lessons->unique('discipline_id');
        foreach ($lessons as $lesson) {
            $disciplines[] = $lesson->discipline;
        }
        echo json_encode($disciplines);
    }

    public function getYears(Group $group, Discipline $discipline) {
        $lessons = Lesson::where('group_id', $group->id)
            ->where('discipline_id', $discipline->id)
            ->get()->unique('year_id');
        $years = [];
        foreach ($lessons as $lesson) {
            $years[] = $lesson->year;
        }
        echo json_encode($years);
    }

    public function getTasks(Request $request, Group $group, Discipline $discipline) {
        if (isset($request->filter_year)) {
            $kek = $request->filter_year;
            $lessons = $group->lessons
                ->where('discipline_id', $discipline->id)
                ->where('year_id', $request->filter_year);
        } else {
            $lessons = $group->lessons->where('discipline_id', $discipline->id);
        }
        $arrayTasks = [];
        $tasksCount = 0;
        $arrayHomework = [];
        foreach ($lessons as $lesson) {
            $tempTaskArray = [];
            foreach ($lesson->tasks as $task) {
                $tasksCount++;
                $month = $this->getRusMonthName(intval($task->created_at->format('m')))
                    . ' ' . $task->created_at->format('Y');
                $tempTaskArray[$month][$task->id] = $task->task;

                foreach ($lesson->group->students as $student) {
                    $studentWork = null;
                    foreach ($task->homework as $homework) {
                        if ($student->id == $homework->student_id) {
                            $studentWork = $homework->grade;
                            break;
                        }
                    }
                    $arrayHomework[$student->user->surname
                        . ' ' . $student->user->name
                        . ' ' . $student->user->patronymic][$task->id] = $studentWork;
                }
            }
            $arrayTasks[$lesson->year->start_year . '-' . $lesson->year->end_year] = $tempTaskArray;
        }

        return view('personal.mark.task.show', compact( 'arrayTasks', 'tasksCount', 'arrayHomework'));
    }

    private function getRusMonthName($n)
    {
        $rusMonthNames = [
            1 => 'Январь', 'Февраль', 'Март', 'Апрель',
            'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
            'Октябрь', 'Ноябрь', 'Декабрь'
        ];
        return $rusMonthNames[$n];
    }
}
