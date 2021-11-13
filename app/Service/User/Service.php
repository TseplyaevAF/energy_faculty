<?php


namespace App\Service\User;

use App\Models\Role;
use App\Models\User;
use App\Service\User\Employee\Service as EmployeeService;
use App\Service\User\Student\Service as StudentService;
use App\Service\User\Teacher\Service as TeacherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Service
{
    public function store($data)
    {
        try {
            DB::beginTransaction();
            if ($data['role_id'] == '1') {
                $studentService = new StudentService();
                $student = $studentService->store($data);
                unset($data['student_id_number']);
                unset($data['group_id']);
                $role = Role::firstOrCreate([
                    'student_id' => $student->id,
                ]);
            } else if ($data['role_id'] == '2') {
                $teacherService = new TeacherService();
                $teacher = $teacherService->store($data);
                unset($data['post']);
                unset($data['activity']);
                unset($data['work_experience']);
                unset($data['address']);
                unset($data['chair_id']);
                if (isset($data['disciplines_ids'])) {
                    unset($data['disciplines_ids']);
                }
                $role = Role::firstOrCreate([
                    'teacher_id' => $teacher->id,
                ]);
            } else if ($data['role_id'] == '3') {
                $employeeService = new EmployeeService();
                $employee = $employeeService->store($data);
                unset($data['chair_id']);
                $role = Role::firstOrCreate([
                    'employee_id' => $employee->id,
                ]);
            }
            $data['role_id'] = $role->id;
            $data['password'] = Hash::make($data['password']);
            User::create($data);
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
            if ($data['role_id'] == '1') {
                $student = $user->role->student;
                $studentService = new StudentService();
                $studentService->update($data, $student);
                unset($data['student_id_number']);
                unset($data['group_id']);
            } else if ($data['role_id'] == '2') {
                $teacher = $user->role->teacher;
                $teacherService = new TeacherService();
                $teacher = $teacherService->update($data, $teacher);
                unset($data['post']);
                unset($data['activity']);
                unset($data['work_experience']);
                unset($data['address']);
                unset($data['chair_id']);
                if (isset($data['disciplines_ids'])) {
                    unset($data['disciplines_ids']);
                }
            }
            unset($data['user_id']);
            unset($data['role_id']);
            $user->update($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
        return $user;
    }
}
