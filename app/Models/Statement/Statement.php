<?php

namespace App\Models\Statement;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    use HasFactory;

    protected $guarded = false;

    const FORM_ZACHET = 1;
    const FORM_EXAM = 2;
    const FORM_COURSE = 3;
    const FORM_Internship = 4;
    const FORM_undergraduate_practice = 5;

    const EVAL_ZACHET = 1;
    const EVAL_3 = 2;
    const EVAL_4 = 3;
    const EVAL_5 = 4;
    const EVAL_ABSENCE = 5;

    public static function getControlForms()
    {
        return [
            self::FORM_ZACHET => 'зачет',
            self::FORM_EXAM => 'экзамен',
            self::FORM_COURSE => 'курсовая работа',
            self::FORM_Internship => 'производственная практика',
            self::FORM_undergraduate_practice => 'преддипломная практика'
        ];
    }

    public static function getEvalTypes() {
        return [
            self::EVAL_ZACHET => 'зачтено',
            self::EVAL_3 => 'удовлетворительно',
            self::EVAL_4 => 'хорошо',
            self::EVAL_5 => 'отлично',
            self::EVAL_ABSENCE => 'неявка'
        ];
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function individuals() {
        return $this->hasMany(Individual::class);
    }

    public static function getArrayStatements($statements)
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
}
