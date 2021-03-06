<?php

namespace App\Models\Statement;

use App\Models\Lesson;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = false;

    const FORM_ZACHET = 1;
    const FORM_EXAM = 2;
    const FORM_COURSE = 3;
    const FORM_Internship = 4;
    const FORM_undergraduate_practice = 5;

    public const EVAL_ZACHET = 1;
    public const EVAL_3 = 2;
    public const EVAL_4 = 3;
    public const EVAL_5 = 4;
    public const EVAL_ABSENCE = 5;
    public const EVAL_2 = 6;
    public const EVAL_NOT_CREDITED = 7;

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
            self::EVAL_ABSENCE => 'не явился',
            self::EVAL_2 => 'неудовлетворительно',
            self::EVAL_NOT_CREDITED => 'не зачтено',
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
            $data[$statement->id]['report'] = $statement->report;
            $data[$statement->id]['is_signed'] =
                count($statement->individuals->where('teacher_signature', '!=', null)) != 0 ? true : false;
            $data[$statement->id]['finish_date'] = date('d.m.Y', strtotime($statement->finish_date));
        }
        return $data;
    }

    public static function getStatementInfo($statement, $control) {
        return 'Экзаменационная ведомость №: ' . $statement->id . '\n' .
            'Учебная группа: ' . $statement->lesson->group->title . ', ' .
            $statement->lesson->semester . ' семестр, ' .
            $statement->lesson->year->start_year . '-' .
            $statement->lesson->year->end_year . '\n' .
            'Форма контроля: ' . $control . '\n';

    }
}
