<?php

namespace App\Http\Controllers\Personal\Homework;

use App\Http\Controllers\Controller;

use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Personal\Homework\FeedbackRequest;
use App\Http\Requests\Personal\Homework\StoreRequest;
use App\Models\Lesson;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Service\Homework\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class HomeworkController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        Gate::authorize('isStudent');
        $group = auth()->user()->student->group;
        return view('personal.new-homework.index', compact('group'));
    }

    public function getTasks(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        $lessons = Lesson::filter($filter)->where('teacher_id', '!=', null)->get();
        $data = [];
        foreach ($lessons as $lesson) {
            $data[$lesson->id] = $this->service->getTasks($lesson, auth()->user()->student);
            $data[$lesson->id] += ['lesson_id' => $lesson->id];
        }
        return view('personal.new-homework.task.show', compact('data', 'lesson'));
    }

    public function create(Task $task)
    {
        Gate::authorize('isStudent');
        return view('personal.homework.create', compact('task'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('isStudent');
        $data = $request->validated();

        $this->service->store(auth()->user()->student, $data);

        return response('Задание успешно добавлено!', 200);
    }

    public function loadHomework(Homework $homework) {
        return view('personal.new-homework.load-homework', compact('homework'));
    }

    public function getEduMaterials(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        $lesson = Lesson::filter($filter)->first();
        $files = \App\Service\Task\Service::getEduMaterialsFiles($lesson);
        return view('personal.new-homework.edu-material.show', compact( 'lesson', 'files'));
    }

    public function download($homeworkId, $mediaId, $filename) {
        $homework = Homework::find($homeworkId);
        Gate::authorize('download-homework', [$homework]);
        $media = $homework->getMedia(Homework::PATH)->where('id', $mediaId)->first();
        return response()->file($media->getPath());
    }

    public function feedback(FeedbackRequest $request, Homework $homework) {
        if (!Gate::allows('feedback-homework', [$homework])) {
            return response('У вас нет прав проверять данное задание', 403);
        }
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $homework->update([
                'grade' => $data['grade']
            ]);
            $task = Task::find($data['task_id']);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return response('Работа успешно проверена!', 200);
    }

    public function destroy(Homework $homework)
    {
        Gate::authorize('isStudent');
        $homework->getMedia(Homework::PATH)->first()->delete();
        $homework->delete();
        return response('Файл успешно удален!', 200);
    }
}
