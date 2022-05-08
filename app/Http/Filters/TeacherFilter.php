<?php


namespace App\Http\Filters;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TeacherFilter extends AbstractFilter
{
    public const FULL_NAME = 'full_name';

    protected function getCallbacks(): array
    {
        return [
            self::FULL_NAME => [$this, 'fullName'],
        ];
    }

    public function fullName(Builder $builder, $value)
    {
        $ids = [];
        $users = User::where('role_id', User::ROLE_TEACHER)
            ->whereRaw(
                "concat(surname, ' ', name, ' ', patronymic) ILIKE '%" . $value . "%' "
            )->get();

        foreach ($users as $user) {
            if (isset($user->teacher)) {
                $ids[] = $user->teacher->id;
            }
        }
        $builder->whereIn('id', $ids);
    }
}
