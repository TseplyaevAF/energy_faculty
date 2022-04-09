<?php


namespace App\Http\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ScheduleFilter extends AbstractFilter
{
    public const DAY = 'day';
    public const WEEK_TYPE = 'week_type';
    public const TEACHER = 'teacher';

    protected function getCallbacks(): array
    {
        return [
            self::DAY => [$this, 'day'],
            self::WEEK_TYPE => [$this, 'weekType'],
            self::TEACHER => [$this, 'teacher']
        ];
    }

    public function day(Builder $builder, $value)
    {
        $builder->where('day', $value);
    }

    public function weekType(Builder $builder, $value)
    {
        $builder->where('week_type', $value);
    }

    public function teacher(Builder $builder, $value)
    {
        $ids = [];
        $user = User::where('surname', $value)->first();
        if (isset($user) && isset($user->teacher)) {
            $teacher = $user->teacher;
            $lessons = $teacher->lessons;
            $lessons->transform(function ($lesson) {
                return $lesson->schedules->pluck('lesson_id')->unique('lesson_id');
            });
            $ids = [];
            foreach ($lessons as $lesson) {
                foreach ($lesson as $item) {
                    $ids[] = $item;
                }
            }
        }
        $builder->whereIn('lesson_id', $ids);
    }
}
