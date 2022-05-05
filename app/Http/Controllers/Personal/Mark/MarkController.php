<?php

namespace App\Http\Controllers\Personal\Mark;

use App\Exports\IndividualsExport;
use App\Exports\SemesterStatementsByStudentExport;
use App\Exports\SemesterStatementsExport;
use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Filters\StatementFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Personal\Mark\UpdateParentsContactsRequest;
use App\Http\Resources\StudentResource;
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
        $teacher = auth()->user()->teacher;
        $groups = $teacher->myGroups;
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

    public function getSemesterStatements(Group $group, $semester) {
        $data = [
            'group' => $group->id,
            'semester' => $semester,
        ];
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        $statements = Statement::filter($filter)->orderBy('updated_at', 'desc')->get()->where('report', '!=', null);

        $studentsMarks = self::getStudentsMarksFromStatements($group->students, $statements);

        return view('personal.mark.semester.show', compact('statements', 'studentsMarks'));
    }

    public function downloadSemesterStatements(Request $request, Group $group, $semester) {
        $data = [
            'group' => $group->id,
            'semester' => $semester,
        ];
        $filter = app()->make(StatementFilter::class, ['queryParams' => array_filter($data)]);
        $statements = Statement::filter($filter)->orderBy('updated_at', 'desc')->get()->where('report', '!=', null);
        $input = $request->input();
        if (isset($input['student_id'])) {
            $student = Student::find($input['student_id']);
            $studentMarks = self::getStudentsMarksFromStatements([$student], $statements);
            $fileName = 'Отчёт по экзаменам и зачетам студента ' . $student->user->fullName() . '.xlsx';
            $file =  Excel::raw(new SemesterStatementsByStudentExport($studentMarks, $statements), 'Xlsx');

        } else {
            $studentsMarks = self::getStudentsMarksFromStatements($group->students, $statements);
            $fileName = 'Отчёт по экзаменам и зачетам ' . $semester . ' семестра группы ' . $group->title . '.xlsx';
            $file =  Excel::raw(new SemesterStatementsExport($studentsMarks, $statements), 'Xlsx');
        }
        return response()->json([
            'file_name' => $fileName,
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($file)
        ]);
    }

    private function getStudentsMarksFromStatements($students, $statements) {
        $studentsMarks = [];
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
        return $studentsMarks;
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

    public function getTasks(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);

        $lesson = Lesson::filter($filter)->first();

        $data = \App\Service\Task\Service::getTasks($lesson);

        return view('personal.mark.task.show', compact( 'data', 'lesson'));
    }

    public function getParentsContacts(Student $student) {
        $parents = json_decode($student->parents);
        return view('personal.mark.show-parents-contacts', compact( 'parents', 'student'));
    }

    public function updateParentsContacts(UpdateParentsContactsRequest $request, Student $student) {
        $data = $request->validated();
        $student->update([
            'parents' => json_encode($data['parents'])
        ]);
        return response('Данные о контактах родителей были обновлены', 200);
    }
}
