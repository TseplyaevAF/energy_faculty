<?php

namespace App\Http\Controllers\Personal\StudentProgress;

use App\Exports\StudentsTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personal\StudentProgress\StoreRequest;
use App\Imports\StudentProgressImport;
use App\Models\Group\Group;
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

    public function getMonthTypes() {
        echo json_encode($this->service->getMonthTypes());
    }

    public function downloadStudentsTemplate(Group $group) {
        $fileName = 'Студенты группы ' . $group->title . '.xlsx';
        $file =  Excel::raw(new StudentsTemplateExport($group->students), 'Xlsx');
        return response()->json([
            'file_name' => $fileName,
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($file)
        ]);
    }
}
