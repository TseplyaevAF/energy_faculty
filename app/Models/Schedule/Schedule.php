<?php

namespace App\Models\Schedule;

use App\Models\Group\Group;
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
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            7 => 'Воскресенье'
        ];
    }

    public static function getWeekTypes()
    {
        return [
            self::WEEK_UP => 'Верхняя',
            self::WEEK_LOW => 'Нижняя'
        ];
    }

    public function discipline() {
        return $this->belongsTo(Discipline::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function class_type() {
        return $this->belongsTo(ClassType::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
