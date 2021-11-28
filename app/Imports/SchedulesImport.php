<?php

namespace App\Imports;

use App\Models\Schedule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SchedulesImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $data = [];
        foreach ($rows as $row) 
        {
            if (isset($row[5]) && $row[5] != 'Вид занятий') {
                array_push($data, $row);
            } else {
                continue;
            }
            // Schedule::create([
            //     'name' => $row[0],
            // ]);
        }
        dd($data);
    }
}
