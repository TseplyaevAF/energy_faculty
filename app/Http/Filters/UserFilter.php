<?php


namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter
{
    public const FULL_NAME = 'full_name';
    public const ROLE_ID = 'role_id';
    public const USER_ID = 'user_id';

    protected function getCallbacks(): array
    {
        return [
            self::ROLE_ID => [$this, 'roleId'],
            self::USER_ID => [$this, 'userId'],
            self::FULL_NAME => [$this, 'fullName'],
        ];
    }

    public function roleId(Builder $builder, $value)
    {
        switch ($value) {
            case "-1":
                $builder->whereHas('role', function (Builder $query) {
                    $query->where('role_default', 'admin');
                });
                break;
            case "1":
                $builder->whereHas('role', function (Builder $query) {
                    $query->where('student_id', '!=', null);
                });
                break;
            case "2":
                $builder->whereHas('role', function ($query) {
                    $query->where('teacher_id', '!=', null);
                });
                break;
        }
    }

    public function userId(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }

    public function fullName(Builder $builder, $value)
    {
        $builder->where(function($query) use($value) {
            $query->where('name', 'like', "%{$value}%")
            ->orWhere('surname', 'like', "%{$value}%")
            ->orWhere('patronymic', 'like', "%{$value}%");
        });
    }
}
