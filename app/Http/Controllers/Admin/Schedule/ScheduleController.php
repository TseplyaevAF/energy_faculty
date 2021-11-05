<?php

namespace App\Http\Controllers\Admin\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Schedule\UpdateRequest;
use App\Models\Chair;
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

class ScheduleController extends Controller
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $chairs = Chair::all();
        $groups = Group::all();
        foreach ($chairs as $chair) {
            $chairsIds[] = $chair->id;
        }
        return view('admin.schedule.index', compact('chairs', 'groups', 'chairsIds'));
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
        return view('admin.schedule.edit', compact('schedule', 'group', 'week_types', 'days', 'class_times', 'disciplines', 'teachers', 'class_types', 'classrooms'));
    }

    public function update(UpdateRequest $request, Schedule $schedule) {
        $data = $request->validated();
        try {
            $this->service->update($data, $schedule);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        return redirect()->route('admin.schedule.group.show', ['group' => $data['group_id']]);
    }
}
