<?php

namespace Database\Seeders;

use App\Models\Chair;
use App\Models\ClassTime;
use App\Models\Discipline;
use App\Models\Group\Group;
use App\Models\Role;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
//        User::factory(100)->create();
        $pathJsons = [
            'class_times' => 'assets/class_times.json',
            'roles' => 'assets/roles.json',
            'chairs' => 'assets/chairs.json',
            'groups' => 'assets/groups.json',
            'disciplines' => 'assets/disciplines.json'
        ];
        foreach ($pathJsons as $key => $path) {
            $array = json_decode(File::get(public_path($path)), true);
            if ($key == 'class_times') {
                self::createLessonTime($array);
                continue;
            }
            if ($key == 'roles') {
                self::createUserRoles($array);
                continue;
            }
            if ($key == 'chairs') {
                self::createChairs($array);
                continue;
            }
            if ($key == 'groups') {
                self::createGroups($array);
                continue;
            }
            if ($key == 'disciplines') {
                self::createDisciplines($array);
                continue;
            }
        }
    }

    // создать расписание звонков
    public function createLessonTime($classTimes) {
        foreach ($classTimes as $classTime) {
            ClassTime::firstOrcreate([
                'start_time' => $classTime['start_time'],
                'end_time' => $classTime['end_time']
            ]);
        }
    }

    // создать кафедры
    public function createChairs($chairs) {
        foreach ($chairs as $chair) {
            Chair::firstOrcreate([
                'title' => $chair['title'],
                'phone_number' => $chair['phone_number'],
                'address' => $chair['address'],
                'email' => $chair['email']
            ]);
        }
    }

    // создать группы
    public function createGroups($groups) {
        foreach ($groups as $group) {
            $chair = Chair::all()->where('title', $group['chair'])->first();
            Group::firstOrcreate([
                'title' => $group['title'],
                'chair_id' => $chair->id,
                'course' => $group['course'],
                'semester' => $group['semester'],
            ]);
        }
    }

    // создать дисциплины
    public function createDisciplines($disciplines) {
        foreach ($disciplines as $discipline) {
            Discipline::firstOrcreate([
                'title' => $discipline['title']
            ]);
        }
    }

    // создать роли пользователей
    public function createUserRoles($rolesArray) {
        foreach ($rolesArray as $role) {
            Role::firstOrcreate([
                'name' => $role['name']
            ]);
        }
    }
}
