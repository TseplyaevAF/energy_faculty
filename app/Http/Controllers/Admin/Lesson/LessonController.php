<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Admin\Lesson\StoreRequest;
use App\Http\Resources\LessonResource;
use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Teacher\Teacher;
use App\Models\Year;
use App\Service\Lesson\Service;
use DataTables;

class LessonController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $chairs = Chair::all();
        return view('admin.lesson.index', compact('chairs'));
    }

    public function getChairLoad(FilterRequest $request, Chair $chair, Year $year)
    {
        if ($request->ajax()) {
            $data = $request->validated();
            $data += [
                'chair_id' => $chair->id,
                'year_id' => $year->id,
            ];
            $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
            $data = LessonResource::collection(Lesson::filter($filter)->orderBy('updated_at', 'desc')->get());
            return DataTables::of($data)
                ->make(true);
        }
        return view('admin.lesson.get-chair-load', compact('chair', 'year'));
    }

    public function create()
    {
        $data = [
            'groups' => Group::all(),
            'disciplines' => Discipline::all(),
            'teachers' => Teacher::all(),
            'semesters' => range(1, 8),
            'years' => Year::all()
        ];
        return view('admin.lesson.create', compact( 'data'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            $this->service->store($data);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.lesson.index');
    }
}
