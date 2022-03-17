<?php

namespace App\Models;

use App\Models\Statement\Individual;
use App\Models\Statement\Statement;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSheet extends Model
{
    use HasFactory;

    protected $guarded = false;
    public $timestamps = false;

    public function individual() {
        return $this->belongsTo(Individual::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public static function getExamSheetInfo($sheet) {
        return 'Экзаменационный лист студенту: ' . $sheet->student->user->surname . ' ' .
            $sheet->student->user->name . ' ' . $sheet->student->user->patronymic . '\n' .
            '№ зачетной книжки: ' . $sheet->student->student_id_number . '\n' .
            'По дисциплине: ' . $sheet->individual->statement->lesson->discipline->title . '\n' .
            'Форма контроля: ' . $sheet->individual->control_form . '\n' .
            'Преподаватель: ' . $sheet->individual->statement->lesson->teacher->user->surname . '\n';
    }

    public static function getArrayExamSheets($exam_sheets)
    {
        $data = [];
        $controlForms = Statement::getEvalTypes();
        foreach ($exam_sheets as $exam_sheet) {
            $data[$exam_sheet->id]['id'] = $exam_sheet->id;
            $data[$exam_sheet->id]['statement_id'] = $exam_sheet->individual->statement_id;
            $data[$exam_sheet->id]['studentFIO'] = $exam_sheet->student->user->surname . ' ' .
                $exam_sheet->student->user->name . ' ' . $exam_sheet->student->user->patronymic;
            $data[$exam_sheet->id]['group'] = $exam_sheet->individual->statement->lesson->group;
            $data[$exam_sheet->id]['discipline'] = $exam_sheet->individual->statement->lesson->discipline;
            $data[$exam_sheet->id]['old_eval'] = $controlForms[$exam_sheet->individual->eval];
        }
        return $data;
    }
}
