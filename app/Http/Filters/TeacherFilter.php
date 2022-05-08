<?php


namespace App\Http\Filters;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TeacherFilter extends AbstractFilter
{
    public const SURNAME = 'surname';

    protected function getCallbacks(): array
    {
        return [
            self::SURNAME => [$this, 'surname'],
        ];
    }

    public function surname(Builder $builder, $value)
    {
        $ids = [];
        $users = User::where('role_id', User::ROLE_TEACHER)
            ->where('surname', $value)
            ->get();
        foreach ($users as $user) {
            if (isset($user->teacher)) {
                $ids[] = $user->teacher->id;
            }
        }
        $builder->whereIn('id', $ids);
    }
}
