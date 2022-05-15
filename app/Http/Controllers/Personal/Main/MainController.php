<?php

namespace App\Http\Controllers\Personal\Main;

use App\Http\Controllers\Controller;
use App\Models\Schedule\ClassTime;
use App\Models\Schedule\Schedule;
use App\Models\User;
use App\Service\Personal\Schedule\Service;

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
            $schedule = Schedule::getSchedule($group);
            $scheduleEven = $schedule['even'];
            $scheduleOdd= $schedule['odd'];
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
