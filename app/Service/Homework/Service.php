<?php


namespace App\Service\Homework;

use App\Models\Student\Homework;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Service
{
    public function store($student, $data)
    {
        try {
            DB::beginTransaction();
            $media = $student->addMedia($data['homework'])->toMediaCollection(Homework::PATH);
            $data['homework'] = $media->getUrl();
            $data['student_id'] = $student->id;
            Homework::firstOrCreate($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $news)
    {

    }
}
