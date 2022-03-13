<?php

namespace App\Http\Controllers\Personal\ExamSheet;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;
use Illuminate\Support\Facades\Gate;

class ExamSheetController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $exam_sheets = ExamSheet::where('student_id', $student->id)->get();
        return view('personal.exam_sheet.index', compact('exam_sheets', 'student'));
    }

    public function show(ExamSheet $sheet) {
        Gate::authorize('isTeacher');
        Gate::authorize('show-exam-sheet', [$sheet]);
        $studentFIO = $sheet->student->user->surname . ' ' .
            $sheet->student->user->name . ' ' . $sheet->student->user->patronymic;
        return view('personal.exam_sheet.show', compact('sheet', 'studentFIO'));
    }
}
