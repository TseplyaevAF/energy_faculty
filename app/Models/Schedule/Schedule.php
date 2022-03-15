<?php

namespace App\Models\Schedule;

use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Teacher\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = false;

    CONST WEEK_UP = 1;
    CONST WEEK_LOW = 2;

    public static function getDays()
    {
        return [
            1 => 'ПН',
            2 => 'ВТ',
            3 => 'СР',
            4 => 'ЧГ',
            5 => 'ПТ',
            6 => 'СБ'
        ];
    }

    public static function getWeekTypes()
    {
        return [
            self::WEEK_UP => 'Верхняя',
            self::WEEK_LOW => 'Нижняя'
        ];
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function class_type() {
        return $this->belongsTo(ClassType::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
