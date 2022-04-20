<?php

namespace App\Http\Controllers\Employee\Group;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Group\Group;
use App\Models\Teacher\Teacher;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = Group::all()->where('chair_id', auth()->user()->employee->chair_id);
            $data = [];
            foreach ($groups as $group) {
                $data[$group->id] = self::getGroupInfo($group);
            }
            return DataTables::of($data)
                ->make(true);
        }
        return view('employee.group.index');
    }

    private function getGroupInfo($group) {
        $headman = $group->getHeadman();
        $curator = $group->teacher;
        return [
            'id' => $group->id,
            'group' => $group->title,
            'headman' => isset($headman) ? $headman->user->surname
                . ' ' . $headman->user->name
                . ' ' . $headman->user->patronymic : null,
            'curator' => isset($curator) ? $curator->user->surname
                . ' ' . $curator->user->name
                . ' ' . $curator->user->patronymic : null,
        ];
    }

    public function getTeachers(Chair $chair, Group $group) {
        $teachers = Teacher::where('chair_id', $chair->id)->get();
        $teachersArray = [];
        foreach ($teachers as $teacher) {
            $teachersArray[$teacher->id] = $teacher->user->surname
                . ' ' . $teacher->user->name
                . ' ' . $teacher->user->patronymic;
        }
        $curator = $group->teacher;
        return view('employee.group.set-new-curator',
            compact('teachersArray', 'group', 'curator'));
    }

    public function setNewCurator(Request $request, Group $group) {
        $group->update([
            'teacher_id' => $request->teacher_id
        ]);
        return response('Куратор успешно назначен!', 200);
    }
}
