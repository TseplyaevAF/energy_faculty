<?php


namespace App\Http\Filters;

use App\Models\Chair;
use Illuminate\Database\Eloquent\Builder;

class LessonFilter extends AbstractFilter
{
    public const GROUP = 'group_id';
    public const SEMESTER = 'semester';
    public const YEAR = 'year_id';
    public const TEACHER = 'teacher_id';
    public const DISCIPLINE = 'discipline_id';
    public const CHAIR = 'chair_id';

    protected function getCallbacks(): array
    {
        return [
            self::GROUP => [$this, 'group'],
            self::SEMESTER => [$this, 'semester'],
            self::YEAR => [$this, 'year'],
            self::TEACHER => [$this, 'teacher'],
            self::DISCIPLINE => [$this, 'discipline'],
            self::CHAIR => [$this, 'chair'],
        ];
    }

    public function group(Builder $builder, $value)
    {
        $builder->where('group_id', $value);
    }

    public function teacher(Builder $builder, $value)
    {
        $builder->where('teacher_id', $value);
    }

    public function semester(Builder $builder, $value)
    {
        $builder->where('semester', $value);
    }

    public function year(Builder $builder, $value)
    {
        $builder->where('year_id', $value);
    }

    public function discipline(Builder $builder, $value)
    {
        $builder->where('discipline_id', $value);
    }

    public function chair(Builder $builder, $value)
    {
        $ids = [];
        $chair = Chair::find($value);
        if (isset($chair)) {
            $groups = $chair->groups;
            $lessonsGroups = $groups->transform(function ($group) {
                return $group->lessons->pluck('id');
            });
            $ids = [];
            foreach ($lessonsGroups as $lessonsGroup) {
                foreach ($lessonsGroup as $item) {
                    $ids[] = $item;
                }
            }
        }
        $builder->whereIn('id', $ids);
    }
}
