<?php


namespace App\Http\Filters;

use App\Models\Group\Group;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Builder;

class StatementFilter extends AbstractFilter
{
    public const GROUP = 'group';
    public const SEMESTER = 'semester';

    protected function getCallbacks(): array
    {
        return [
            self::GROUP => [$this, 'group'],
            self::SEMESTER => [$this, 'semester']
        ];
    }

    public function group(Builder $builder, $value)
    {
        $group = Group::find($value);
        $lessons = $group->lessons;
        $ids = self::getLessonsIds($lessons);
        $builder->whereIn('lesson_id', $ids);
    }

    public function semester(Builder $builder, $value)
    {
        $lessons = Lesson::where('semester', $value)->get();
        $ids = self::getLessonsIds($lessons);
        $builder->whereIn('lesson_id', $ids);
    }

    private function getLessonsIds($lessons) {
        $ids = [];
        $lessons->transform(function ($lesson) {
            return $lesson->statements->pluck('lesson_id')->unique('lesson_id');
        });
        foreach ($lessons as $lesson) {
            foreach ($lesson as $item) {
                $ids[] = $item;
            }
        }
        return $ids;
    }
}
