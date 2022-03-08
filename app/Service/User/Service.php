<?php


namespace App\Service\User;

use App\Models\User;
use App\Service\User\Employee\Service as EmployeeService;
use App\Service\User\Student\Service as StudentService;
use App\Service\User\Teacher\Service as TeacherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\Models\Media;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $data['user_id'] = $user->id;
            if ($data['role_id'] == User::ROLE_STUDENT) {
                $studentService = new StudentService();
                $studentService->store($data);
            } else if ($data['role_id'] == User::ROLE_TEACHER) {
                $teacherService = new TeacherService();
                $teacherService->store($data);
            } else if ($data['role_id'] == User::ROLE_EMPLOYEE) {
                $employeeService = new EmployeeService();
                $employeeService->store($data);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $user)
    {
        try {
            DB::beginTransaction();
            if ($data['role_id'] == User::ROLE_STUDENT) {
                $student = $user->student;
                if (isset($student->group->headman) && $data['group_id'] != $student->group_id) {
                    $student->group->update([
                        'headman' => null
                    ]);
                }
                $studentService = new StudentService();
                $studentService->update($data, $student);
            } else if ($data['role_id'] == User::ROLE_TEACHER) {
                $teacher = $user->teacher;
                $teacherService = new TeacherService();
                $teacherService->update($data, $teacher);
            }
            unset($data['user_id']);
            unset($data['role_id']);
            $user->update($data);
            if (isset($data['avatar'])) {
                $user->addMedia($data['avatar'])->toMediaCollection();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $user;
    }
}
