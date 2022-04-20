<?php

namespace App\Http\Controllers\Personal\Task;

use App\Http\Controllers\Controller;
use App\Http\Filters\LessonFilter;
use App\Http\Requests\Admin\Lesson\FilterRequest;
use App\Http\Requests\Personal\Task\StoreRequest;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Service\Task\Service;
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

        return view('personal.new-task.index', compact( 'disciplines'));
    }

    public function getGroups(Discipline $discipline) {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->where('discipline_id', $discipline->id)
            ->get()->unique('group_id');
        $groups = [];
        foreach ($lessons as $lesson) {
            $groups[] = $lesson->group;
        }
        echo json_encode($groups);
    }

    public function getSemesters(Discipline $discipline, Group $group) {
        Gate::authorize('isTeacher');
        $teacher = auth()->user()->teacher;
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->where('discipline_id', $discipline->id)
            ->where('group_id', $group->id)
            ->get()->unique('semester');
        $semesters = [];
        foreach ($lessons as $lesson) {
            $semesters[] = $lesson->semester;
        }
        echo json_encode($semesters);
    }

    public function getTasks(FilterRequest $request) {
        $data = $request->validated();
        $filter = app()->make(LessonFilter::class, ['queryParams' => array_filter($data)]);
        $lesson = Lesson::filter($filter)->first();
        $data = $this->service->getTasks($lesson);
        $data += ['lesson_id' => $lesson->id];
        return view('personal.new-task.task.show', compact('data', 'lesson'));
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


    public function store(StoreRequest $request)
    {
        Gate::authorize('isTeacher');

        $data = $request->validated();

        $this->service->store($data);

        return response('Задание успешно добавлено!', 200);
    }

    public function download($taskId, $mediaId, $filename) {
        $task = Task::find($taskId);
        Gate::authorize('download-task', [$task]);
        $media = $task->getMedia(Task::PATH)->where('id', $mediaId)->first();
        // сервим файл из медиа-модели
        return isset($media) ? response()->file($media->getPath(), [
            'Cache-Control' => 'no-cache, no-cache, must-revalidate',
            ]) : abort(404);
    }

    public function complete(Task $task) {
        Gate::authorize('show-task', [$task]);
        $task->update([
            'status' => 1,
        ]);
        return redirect()->route('personal.task.index');
    }
}
