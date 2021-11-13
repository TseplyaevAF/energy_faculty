<?php

namespace App\Http\Controllers\Employee\Schedule\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\Schedule\UpdateRequest;
use App\Models\Classroom;
use App\Models\ClassTime;
use App\Models\ClassType;
use App\Models\Day;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Schedule;
use App\Models\Teacher\Teacher;
use App\Models\WeekType;
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
        $days = Day::all();
        $class_times = ClassTime::all();
        // расписание по чётной неделе
        $scheduleEven = Schedule::where('group_id', $group->id)->where('week_type_id', 1)->get();
        // расписание по нечётной неделе
        $scheduleOdd = Schedule::where('group_id', $group->id)->where('week_type_id', 2)->get();
        return view('employee.schedule.group.show', compact('group', 'days', 'class_times', 'scheduleEven', 'scheduleOdd'));
    }

    public function edit(Schedule $schedule) {
        $group = $schedule->group;
        $week_types = WeekType::all();
        $days = Day::all();
        $class_times = ClassTime::all();
        $teachers = Teacher::all();
        $disciplines = Discipline::all();
        $class_types = ClassType::all();
        $classrooms = Classroom::all();
        return view('employee.schedule.group.edit', compact('schedule', 'group', 'week_types', 'days', 'class_times', 'disciplines', 'teachers', 'class_types', 'classrooms'));
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
