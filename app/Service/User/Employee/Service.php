<?php


namespace App\Service\User\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $array = [
                'chair_id' => $data['chair_id'],
            ];
            $employee = Employee::firstOrCreate($array);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $employee;
    }
}
