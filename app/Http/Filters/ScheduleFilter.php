<?php


namespace App\Http\Filters;

use App\Models\Group\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ScheduleFilter extends AbstractFilter
{
    public const DAY = 'day';
    public const WEEK_TYPE = 'week_type';
    public const TEACHER = 'teacher';
    public const GROUP = 'group';

    protected function getCallbacks(): array
    {
        return [
            self::DAY => [$this, 'day'],
            self::WEEK_TYPE => [$this, 'weekType'],
            self::GROUP => [$this, 'group'],
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
        $user = User::where('role_id', User::ROLE_TEACHER)
            ->whereRaw(
                "concat(surname, ' ', name, ' ', patronymic) ILIKE '%" . $value . "%' "
            )->first();
        if (isset($user)) {
            $ids = self::getLessonsIds($user->teacher->lessons);
        }
        $builder->whereIn('lesson_id', $ids);
    }

    public function group(Builder $builder, $value)
    {
        $ids = [];
        $group = Group::where('title', 'LIKE' , $value)
            ->orWhere('title', 'ILIKE' , $value)
            ->first();
        if (isset($group)) {
            $ids = self::getLessonsIds($group->lessons);
        }

        $builder->whereIn('lesson_id', $ids);
    }

    private function getLessonsIds($lessons) {
        $lessons->transform(function ($lesson) {
            return $lesson->schedules->pluck('lesson_id')->unique('lesson_id');
        });
        $ids = [];
        foreach ($lessons as $lesson) {
            foreach ($lesson as $item) {
                $ids[] = $item;
            }
        }
        return $ids;
    }
}
