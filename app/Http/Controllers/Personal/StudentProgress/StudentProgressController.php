<?php

namespace App\Http\Controllers\Personal\StudentProgress;

use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\StudentProgress\StoreRequest;
use App\Imports\StudentProgressImport;
use App\Service\StudentProgress\Service;
use Maatwebsite\Excel\Facades\Excel;

class StudentProgressController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function import(StoreRequest $request)
    {
        $data = $request->validated();
        try {
        ini_set('memory_limit', '-1');
        Excel::import(new StudentProgressImport($data['lesson_id'], $data['monthNumber']), $data['student_progress']);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), '403');
        }
    }
}
