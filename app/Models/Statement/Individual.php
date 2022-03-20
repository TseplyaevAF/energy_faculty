<?php

namespace App\Models\Statement;

use App\Models\ExamSheet;
use App\Models\Student\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;

    protected $guarded = false;

    static public function getIndividualInfo($individual) {
        return [
            'Студент' => $individual['studentFIO'],
            '№ студенческого билета: ' => $individual['student_id_number']
        ];
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function statement() {
        return $this->belongsTo(Statement::class);
    }

    public function exam_sheet() {
        return $this->hasOne(ExamSheet::class);
    }

    private static function getIndividual($individual) {
        $data[$individual->id]['id'] = $individual->id;
        $data[$individual->id]['studentFIO'] = $individual->student->user->surname . ' ' .
            $individual->student->user->name . ' ' . $individual->student->user->patronymic;
        $data[$individual->id]['student_id_number'] = $individual->student->student_id_number;
        $data[$individual->id]['evaluation'] = !isset($individual->eval) ? '' : $individual->eval;
        return $data;
    }

    public static function getArrayIndividuals($individuals)
    {
        $data = [];
        foreach ($individuals as $individual) {
            if (!isset($individual->teacher_signature)) {
                $data += self::getIndividual($individual);
            }
        }
        return $data;
    }

    public static function getArrayCompletedSheets($individuals)
    {
        $data = [];
        foreach ($individuals as $individual) {
            $data += self::getIndividual($individual);
            $data[$individual->id]['exam_finish_date'] = !isset($individual->exam_finish_date) ?
                '' : $individual->exam_finish_date;
        }
        return $data;
    }
}
