<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Discipline;
use App\Models\Lesson;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Service\Task\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;

class TaskController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        $disciplines = $teacher->disciplines->unique('id');

        return view('personal.new-task.index', compact( 'disciplines', 'teacher'));
    }

    public function getTasks(FilterRequest $request) {
        $data = $request->validated();
        $data += [
            'teacher_id' => auth()->user()->teacher->id
        ];
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        $lesson = Lesson::filter($filter)->first();
        $data = $this->service->getTasks($lesson);
        $data += ['lesson_id' => $lesson->id];
        return view('personal.new-task.task.show', compact('data', 'lesson'));
    }

    public function getEduMaterials(FilterRequest $request) {
        $data = $request->validated();
        $data += [
            'teacher_id' => auth()->user()->teacher->id
        ];
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        $lesson = Lesson::filter($filter)->first();
        $files = $this->service->getEduMaterialsFiles($lesson);
        return view('personal.new-task.edu-material.show', compact( 'lesson', 'files'));
    }

    public function loadHomework(Homework $homework) {
        return view('personal.new-task.load-homework', compact('homework'));
    }

    public function create()
    {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        foreach ($teacher->lessons as  $lesson) {
            $lessons[$lesson->id]['discipline']['id'] = $lesson->discipline->id;
            $lessons[$lesson->id]['discipline']['title'] = $lesson->discipline->title;

            $lessons[$lesson->id]['group']['id'] = $lesson->group->id;
            $lessons[$lesson->id]['group']['title'] = $lesson->group->title;

            $lessons[$lesson->id]['semester'] = $lesson->semester;
        }

        return view('personal.task.create', compact('lessons'));
    }

    public function createLesson() {
        $disciplines = Discipline::all();
        return view('personal.new-task.create-lesson', compact('disciplines'));
    }

    public function createTeacherLesson(\App\Http\Requests\Admin\Lesson\StoreRequest $request) {
        $data = $request->validated();
        $lesson = Lesson::where('discipline_id', $data['discipline_id'])
                        ->where('group_id', $data['group_id'])
                        ->where('semester', $data['semester'])
                        ->where('teacher_id', null)->first();
        if (!isset($lesson)) {
            return response('Данная нагрузка занята', 403);
        } else {
            $lesson->update([
                'teacher_id' => $data['teacher_id']
            ]);
            return response('Нагрузка успешно добавлена! Страница будет перезагружена', 200);
        }
    }


    public function store(StoreRequest $request)
    {
        Gate::authorize('isTeacher');

        $data = $request->validated();

        $this->service->store($data, Task::TEST);

        return response('Задание успешно добавлено!', 200);
    }

    public function storeEduMaterial(Request $request) {
        Gate::authorize('isTeacher');

        try {
            $request->validate([
                'file' => 'required|file'
            ]);
        } catch (\Exception $exception) {
            return response('Необходимо выбрать файл', 404);
        }

        $rules = [];
        $pdfMaxSize = 1024 * 10; // не более 10 MB
        $videoMaxSize = 1024 * 500; // не более 500 MB

        $file = $request->file('file');
        if(in_array($file->getMimeType(), ['application/pdf'])) {
            $rules["file"] = "max:$pdfMaxSize";
        } else if (in_array($file->getMimeType(), ['video/mp4', 'video/avi'])){
            $rules["file"] = "max:$videoMaxSize";
        } else {
            $rules["file"] = 'mimes:application/pdf,mp4';
        }

        try {
            $request->validate($rules);
        } catch (\Exception $exception) {
            if ($rules['file'] == "max:$pdfMaxSize") {
                return response('Файл должен иметь размер не более ' . $pdfMaxSize/1000 . ' Мб', 404);
            }
            if ($rules['file'] == "max:$videoMaxSize") {
                return response('Файл должен иметь размер не более ' . $videoMaxSize/1000 . ' Мб', 404);
            }
            if ($rules['file'] == "mimes:application/pdf,mp4") {
                return response('Загрузить можно файл с одним из следующих форматов: pdf, mp4', 404);
            }
        }

        $this->service->store([
            'lesson_id' => $request->lesson_id,
            'task' => $request->file
        ], Task::LEC);

        return response('Учебный материал успешно добавлен!', 200);
    }

    public function loadEduMaterial(Task $eduMaterial) {
        return view('personal.new-task.load-video-edu-material', compact('eduMaterial'));
    }

    public function download($taskId, $mediaId, $filename) {
        $task = Task::find($taskId);
        Gate::authorize('download-task', [$task]);
        $media = $task->getMedia(Task::PATH)->where('id', $mediaId)->first();
        $response = response()->file($media->getPath());
        return $response;
        // сервим файл из медиа-модели
//        return isset($media) ? response()->file($media->getPath(), [
//            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
//            ]) : abort(404);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('isTeacher');
        if (count($task->homework) != 0) {
            return response('Удалить невозможно', 403);
        }
        $task->getMedia(Task::PATH)->first()->delete();
        $task->delete();
        return response('Файл успешно удален!', 200);
    }

    public function complete(Task $task) {
        Gate::authorize('show-task', [$task]);
        $task->update([
            'status' => 1,
        ]);
        return redirect()->route('personal.task.index');
    }
}
