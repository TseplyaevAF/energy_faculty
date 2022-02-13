<?php

namespace App\Models\Teacher;

use App\Models\Discipline;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherDiscipline extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    // получить массив из учебных предметов (Преподаватель - Дисциплина)
    public static function getSubjects($teacherDisciplines) {
        $subjects = [];
        foreach ($teacherDisciplines as $teacherDiscipline) {
            $item['discipline'] = $teacherDiscipline->discipline;
            $item['teacher'] = $teacherDiscipline->teacher;
            $subjects[$teacherDiscipline->id] = $item;
            $item = [];
        }
        return $subjects;
    }
}
