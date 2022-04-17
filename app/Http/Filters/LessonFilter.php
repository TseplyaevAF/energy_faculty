<?php


namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class LessonFilter extends AbstractFilter
{
    public const GROUP = 'group_id';
    public const SEMESTER = 'semester';
    public const YEAR = 'year_id';
    public const TEACHER = 'teacher_id';
    public const DISCIPLINE = 'discipline_id';

    protected function getCallbacks(): array
    {
        return [
            self::GROUP => [$this, 'group'],
            self::SEMESTER => [$this, 'semester'],
            self::YEAR => [$this, 'year'],
            self::TEACHER => [$this, 'teacher'],
            self::DISCIPLINE => [$this, 'discipline']
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
}
