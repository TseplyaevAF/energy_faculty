<?php

namespace App\Http\Controllers\Employee\Schedule\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Schedule\UpdateRequest;
use App\Models\Schedule\Classroom;
use App\Models\Schedule\ClassTime;
use App\Models\Schedule\ClassType;
use App\Models\Group\Group;
use App\Models\Schedule\Schedule;
use App\Service\Schedule\Service;

class GroupController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function show(Group $group)
    {
        $days = Schedule::getDays();
        $class_times = ClassTime::all();
        $scheduleEven = [];
        $scheduleOdd = [];

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
        return view('employee.schedule.group.show',
            compact('group', 'days', 'class_times', 'scheduleEven', 'scheduleOdd'));
    }

    public function edit(Schedule $schedule) {
        $group = $schedule->lesson->group;
        $data = [
            'week_types' => Schedule::getWeekTypes(),
            'days' => Schedule::getDays(),
            'class_times' => ClassTime::all(),
            'class_types' => ClassType::all(),
            'classrooms' => Classroom::all(),
            'lessons' => $group->lessons
        ];
        return view('employee.schedule.group.edit',
            compact('schedule','group', 'data'));
    }

    public function update(UpdateRequest $request, Schedule $schedule) {
        $data = $request->validated();
        try {
            $this->service->update($data, $schedule);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('employee.schedule.group.show', ['group' => $data['group_id']]);
    }
}
