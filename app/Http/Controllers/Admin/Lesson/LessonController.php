<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Admin\Lesson\StoreRequest;
use App\Http\Requests\Admin\Lesson\UpdateRequest;
use App\Http\Resources\LessonResource;
use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Teacher\Teacher;
use App\Models\Year;
use App\Service\Lesson\Service;
use DataTables;
use Illuminate\Http\Request;

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

    public function getYears()
    {
        echo json_encode(Year::all());
    }

    public function addYear(Request $request) {
        $data = $request->input();
        $yearInput = explode('-', $data['year']);
        try {
            Year::firstOrCreate([
                'start_year' => $yearInput[0],
                'end_year' => $yearInput[1],
            ]);
        } catch (\Exception $e) {
            return response('Учебный год введён некорректно', 403);
        }

        return response('', 200);
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
            $data = LessonResource::collection(Lesson::filter($filter)->orderBy('created_at', 'asc')->get());
            return DataTables::of($data)
                ->make(true);
        }
        return view('admin.lesson.get-chair-load', compact('chair', 'year'));
    }

    public function create(Chair $chair, Year $year)
    {
        $data = [
            'groups' => Group::where('chair_id', $chair->id)->get(),
            'disciplines' => Discipline::all(),
            'teachers' => Teacher::all(),
            'semesters' => range(1, 8),
            'year' => $year,
            'chair' => $chair,
        ];
        return view('admin.lesson.create', compact( 'data'));
    }

    public function store(StoreRequest $request, Chair $chair, Year $year)
    {
        $data = $request->validated();
        try {
            $this->service->store($data);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.lesson.get-chair-load', [$chair->id, $year->id]);
    }

    public function loadTeachers(Chair $chair, Year $year, Lesson $lesson) {
        $teachers = Teacher::all();
        return view('admin.lesson.ajax-views.set-new-teacher', compact('teachers', 'lesson'));
    }

    public function update(UpdateRequest $request, Chair $chair, Year $year, Lesson $lesson) {
        $data = $request->validated();
        try {
            $this->service->update($data, $lesson);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), '403');
        }
        return response('Преподаватель был успешно сменён', 200);
    }
}
