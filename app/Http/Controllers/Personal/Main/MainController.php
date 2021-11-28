<?php

namespace App\Http\Controllers\Personal\Main;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassTime;
use App\Models\ClassType;
use App\Models\Day;
use App\Models\Discipline;
use App\Models\Schedule;
use App\Models\User;
use App\Models\WeekType;
use App\Http\Requests\Personal\Schedule\UpdateRequest;
use App\Service\Personal\Schedule\Service;
use Illuminate\Support\Facades\Gate;

class MainController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('personal.main.index');
    }

    public function showSchedule()
    {
        $user = auth()->user();
        $days = Day::all();
        $class_times = ClassTime::all();
        if ($user->role_id == User::ROLE_STUDENT) {
            $group = $user->student->group;
            // расписание по чётной неделе
            $scheduleEven = Schedule::where('group_id', $group->id)->where('week_type_id', 1)->get();
            // расписание по нечётной неделе
            $scheduleOdd = Schedule::where('group_id', $group->id)->where('week_type_id', 2)->get();
            return view('personal.main.showSchedule', compact('group','days', 'class_times', 'scheduleEven', 'scheduleOdd'));
        }
        if ($user->role_id == User::ROLE_TEACHER) {
            // расписание по чётной неделе
            $scheduleEven = Schedule::where('teacher_id', $user->teacher->id)->where('week_type_id', 1)->get();
            // расписание по нечётной неделе
            $scheduleOdd = Schedule::where('teacher_id', $user->teacher->id)->where('week_type_id', 2)->get();
            return view('personal.main.showSchedule', compact('days', 'class_times', 'scheduleEven', 'scheduleOdd'));
        }
    }

    public function editSchedule(Schedule $schedule) {
        Gate::authorize('edit-schedule');
        $group = $schedule->group;
        $week_types = WeekType::all();
        $days = Day::all();
        $class_times = ClassTime::all();
        $disciplines = Discipline::all();
        $class_types = ClassType::all();
        $classrooms = Classroom::all();
        return view('personal.main.editSchedule', compact('schedule', 'group', 'week_types', 'days', 'class_times', 'disciplines', 'class_types', 'classrooms'));
    }

    public function updateSchedule(UpdateRequest $request, Schedule $schedule) {
        Gate::authorize('edit-schedule');
        $data = $request->validated();
        $data['teacher_id'] = auth()->user()->teacher->id;
        try {
            $this->service->update($data, $schedule);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('personal.main.schedule')->withSuccess('Расписание успешно обновлено!');
    }
}
