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

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function individuals() {
        return $this->hasMany(Individual::class);
    }
}
