<?php

namespace App\Http\Controllers\Personal\Main;

use App\Http\Controllers\Controller;
use App\Models\Schedule\Classroom;
use App\Models\Schedule\ClassTime;
use App\Models\Schedule\ClassType;
use App\Models\Discipline;
use App\Models\Schedule\Schedule;
use App\Models\User;
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
        $days = Schedule::getDays();
        $class_times = ClassTime::all();
        $scheduleEven = [];
        $scheduleOdd = [];
        if ($user->role_id == User::ROLE_STUDENT) {
            $group = $user->student->group;
            foreach ($group->lessons as $lesson) {
                if ($lesson->semester === $group->semester) {
                    // расписание по чётной неделе
                    foreach ($lesson->schedules->where('week_type', Schedule::WEEK_UP) as $item) {
                        $scheduleEven [] = $item;
                    }
                    // расписание по нечётной неделе
                    foreach ($lesson->schedules->where('week_type', Schedule::WEEK_LOW) as $item) {
                        $scheduleOdd [] = $item;
                    }
                }
            }
            return view('personal.main.showSchedule',
                compact('group','days', 'class_times', 'scheduleEven', 'scheduleOdd'));
        }
        if ($user->role_id == User::ROLE_TEACHER) {
            $teacher = $user->teacher;
            foreach ($teacher->lessons as $lesson) {
                // расписание по чётной неделе
                foreach ($lesson->schedules->where('week_type', Schedule::WEEK_UP) as $item) {
                    $scheduleEven [] = $item;
                }
                // расписание по нечётной неделе
                foreach ($lesson->schedules->where('week_type', Schedule::WEEK_LOW) as $item) {
                    $scheduleOdd [] = $item;
                }
            }
            return view('personal.main.showSchedule',
                compact('days', 'class_times', 'scheduleEven', 'scheduleOdd'));
        }
    }
}
