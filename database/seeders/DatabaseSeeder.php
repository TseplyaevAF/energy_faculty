<?php

namespace Database\Seeders;

use App\Models\ClassTime;
use App\Models\Role;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        User::factory(100)->create();
        self::createLessonTime();
        self::createUserRoles();
    }

    // создать расписание звонков
    public function createLessonTime() {
        $classTimes = ClassTime::all();
        if (isset($classTimes)) {
            foreach ($classTimes as $classTime) {
                $classTime->delete();
            }
        }
        $classTimeArray = [
            [
                'start_time' => "08:30",
                'end_time' => "10:05"
            ],
            [
                'start_time' => "10:15",
                'end_time' => "11:50"
            ],
            [
                'start_time' => "12:00",
                'end_time' => "13:35"
            ],
            [
                'start_time' => "14:35",
                'end_time' => "16:10"
            ],
            [
                'start_time' => "16:20",
                'end_time' => "17:55"
            ],
            [
                'start_time' => "18:05",
                'end_time' => "19:40"
            ]
        ];
        foreach ($classTimeArray as $classTime) {
            ClassTime::create($classTime);
        }

    }

    // создать роли пользователей
    public function createUserRoles() {
        $roles = Role::all();
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role->delete();
            }
        }
        $rolesArray = [
            [
                'name' => "Администратор",
            ],
            [
                'name' => "Студент",
            ],
            [
                'name' => "Преподаватель",
            ],
            [
                'name' => "Сотрудник кафедры",
            ]
        ];
        foreach ($rolesArray as $role) {
            Role::create($role);
        }
    }
}
