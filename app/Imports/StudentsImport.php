<?php

namespace App\Imports;

use App\Service\Group\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try {
            DB::beginTransaction();
            $service = new Service();
            unset($this->data['excel_file']);
            $group = $service->store($this->data);

            foreach ($collection->toArray() as $item) {

            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }
}
