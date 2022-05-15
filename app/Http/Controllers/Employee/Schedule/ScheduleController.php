<?php

namespace App\Http\Controllers\Employee\Schedule;

use App\Exports\ScheduleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Schedule\ImportRequest;
use App\Imports\SchedulesImport;
use App\Models\Group\Group;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function index()
    {
        $chair = session('chair');
        $groups = Group::with('chair')->where('chair_id', $chair->id)->get()->toArray();
        $arrayGroupsByYear = [];
        foreach($groups as $group)
        {
            $arrayGroupsByYear[$group['start_year']][$group['id']] = $group;
        }
        return view('employee.schedule.index', compact( 'arrayGroupsByYear'));
    }

    public function exportTemplate() {
        $fileName = 'Шаблон расписания.xlsx';
        $file =  Excel::raw(new ScheduleExport(), 'Xlsx');
        return response()->json([
            'file_name' => $fileName,
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($file)
        ]);
    }

    public function import(ImportRequest $request) {
        $data = $request->validated();
        try {
            ini_set('memory_limit', '-1');
            Excel::import(new SchedulesImport($data['semester'], $data['group_id']), $data['excel_file']);
        } catch (\Exception $exception) {
            return redirect()->back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->back()->withSuccess('Расписание успешно загружено!');
    }
}
