<?php

namespace App\Models\Schedule;

use App\Models\Lesson;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = false;

    CONST WEEK_UP = 1;
    CONST WEEK_LOW = 2;

    public static function getDays()
    {
        return [
            1 => 'ПН',
            2 => 'ВТ',
            3 => 'СР',
            4 => 'ЧТ',
            5 => 'ПТ',
            6 => 'СБ'
        ];
    }

    public static function getSchedule($group) {
        $scheduleEven = [];
        $scheduleOdd = [];
        $lessons = $group->lessons;
        $course = null;
        $maxSemester = null;
        if ($lessons->count() !== 0) {
            $maxSemester = $lessons->max('semester');
            foreach ($lessons->groupBy('semester')[$maxSemester] as $lesson) {
                // расписание по чётной неделе
                foreach ($lesson->schedules->where('week_type', Schedule::WEEK_UP) as $item) {
                    $scheduleEven [] = $item;
                }
                // расписание по нечётной неделе
                foreach ($lesson->schedules->where('week_type', Schedule::WEEK_LOW) as $item) {
                    $scheduleOdd [] = $item;
                }
            }
            $course = self::getCourse($maxSemester);
        }
        return ['even' => $scheduleEven, 'odd' => $scheduleOdd, 'study_period' => [$course, $maxSemester]];
    }

    private static function getCourse($semester) {
        return intval(floor(($semester+1)/2));
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

    public function class_time() {
        return $this->belongsTo(ClassTime::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
