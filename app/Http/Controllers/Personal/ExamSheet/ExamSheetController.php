<?php

namespace App\Http\Controllers\Personal\ExamSheet;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Validator;

class ExamSheetController extends Controller
{
    public function index()
    {
        Gate::authorize('isStudent');
        $student = auth()->user()->student;
        $individuals = Individual::where('student_id', $student->id)
            ->where('eval', Statement::EVAL_ABSENCE)
            ->where('exam_finish_date', '!=', null)
            ->get();
        foreach ($individuals as $key => $individual) {
            if ($individual->exam_finish_date < $individual->statement->finish_date) {
                unset($individuals[$key]);
            }
        }
        $evals = Statement::getEvalTypes();
        return view('personal.exam_sheet.index', compact('individuals', 'student', 'evals'));
    }

    public function show(ExamSheet $sheet) {
        Gate::authorize('isTeacher');
        Gate::authorize('show-exam-sheet', [$sheet]);
        $studentFIO = $sheet->student->user->surname . ' ' .
            $sheet->student->user->name . ' ' . $sheet->student->user->patronymic;
        return view('personal.exam_sheet.show', compact('sheet', 'studentFIO'));
    }

    public function store(Request $request) {
        Gate::authorize('isStudent');
        $individual_id = $request->individual_id;
        $rules = [
            'individual_id' => 'required|integer|exists:individuals,id'
        ];

        $validator = Validator::make(['individual_id' => $individual_id], $rules);
        if (!$validator->passes()) {
            return response( 404);
        }
        ExamSheet::create([
            'student_id' => auth()->user()->student->id,
            'individual_id' => $individual_id
        ]);

        return redirect()->route('personal.exam_sheet.index');
    }
}
