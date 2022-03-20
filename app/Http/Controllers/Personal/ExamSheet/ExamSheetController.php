<?php

namespace App\Http\Controllers\Personal\ExamSheet;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;
use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Service\Personal\Statement\Service;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Validator;

class ExamSheetController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

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
        $evalTypes = Statement::getEvalTypes();
        $controlForms = Statement::getControlForms();
        return view('personal.exam_sheet.show',
            compact('sheet', 'studentFIO', 'evalTypes', 'controlForms'));
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

    public function sign(Request $request, ExamSheet $examSheet) {
        Gate::authorize('isTeacher');
        try {
            $request->validate([
                'eval' => 'required|integer',
                'private_key' => 'required|file'
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->withError('Необходимо выбрать файл')->withInput();
        }
        $data = [
            'individuals' => [
                [
                    'id' => $examSheet->individual->id,
                    'studentFIO' => $examSheet->student->user->surname . ' ' .
                        $examSheet->student->user->name . ' ' .
                        $examSheet->student->user->patronymic,
                    'evaluation' => $request->eval,
                    'student_id_number' => $examSheet->student->student_id_number
                ]
            ],
            'private_key' => $request->private_key,
            'teacher_id' => auth()->user()->teacher->id
        ];
        try {
            $this->service->signIndividuals($data);
            $examSheet->delete();
            return redirect()->route('personal.main.index');
        }catch (\Exception $exception) {
            return redirect()->back()->withError($exception->getMessage())->withInput();
        }
    }
}
