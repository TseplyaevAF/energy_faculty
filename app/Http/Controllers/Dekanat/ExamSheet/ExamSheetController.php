<?php

namespace App\Http\Controllers\Dekanat\ExamSheet;

use App\Http\Controllers\Controller;
use App\Models\ExamSheet;
use App\Service\Dekanat\Service;
use Illuminate\Http\Request;
use DataTables;

class ExamSheetController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ExamSheet::where('dekan_signature', null)->get();
            $data = ExamSheet::getArrayExamSheets($data);
            return DataTables::of($data)
                ->addColumn('action', function ($query) {
                    $sheet_id = $query['id'];
                    return view('dekanat.exam_sheet.action', compact('sheet_id'));
                })->make(true);
        }
        return view('dekanat.exam_sheet.index');
    }

    public function issue(Request $request, ExamSheet $sheet) {
        try {
            $request->validate([
                'private_key' => 'required|file'
            ]);
        } catch (\Exception $exception) {
            return response('Необходимо выбрать файл', 404);
        }
        $private_key = $request->private_key;
        $data = [
            'sheet' => $sheet,
            'private_key' => $private_key
        ];
        try {
            $this->service->issueSheet($data);
            return response('Допуск успешно выдан!', 200);
        }catch (\Exception $exception) {
            return response($exception->getMessage(), 403);
        }
    }
}
