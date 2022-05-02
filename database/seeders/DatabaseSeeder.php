<?php

namespace Database\Seeders;

use App\Models\Chair;
use App\Models\Discipline;
use App\Models\Employee;
use App\Models\Group\Group;
use App\Models\Lesson;
use App\Models\News\Category;
use App\Models\News\Event;
use App\Models\News\News;
use App\Models\News\NewsTag;
use App\Models\News\Tag;
use App\Models\Role;
use App\Models\Schedule\ClassTime;
use App\Models\Teacher\Teacher;
use App\Models\User;
use App\Models\Year;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $pathJsons = [
            'class_times' => 'assets/class_times.json',
            'roles' => 'assets/roles.json',
            'chairs' => 'assets/chairs.json',
            'groups' => 'assets/groups.json',
            'disciplines' => 'assets/disciplines.json',
            'categories' => 'assets/categories.json',
            'tags' => 'assets/tags.json',
            'users' => 'assets/users.json',
            'lessons' => 'assets/lessons.json',
            'news' => 'assets/news.json',
            'news_tags' => 'assets/news_tags.json',
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
            if ($key == 'categories') {
                self::createCategories($array);
                continue;
            }
            if ($key == 'tags') {
                self::createTags($array);
                continue;
            }
            if ($key == 'users') {
                self::createUsers($array);
                continue;
            }
            if ($key == 'lessons') {
                self::createLessons($array);
                continue;
            }
            if ($key == 'news') {
                self::createPosts($array);
                continue;
            }
            if ($key == 'news_tags') {
                self::createNewsTags($array);
                continue;
            }
        }
//        User::factory(100)->create();
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

    // создать категории
    public function createCategories($categories) {
        foreach ($categories as $category) {
            Category::firstOrcreate([
                'title' => $category['title']
            ]);
        }
    }

    // создать теги
    public function createTags($tags) {
        foreach ($tags as $tag) {
            Tag::firstOrcreate([
                'title' => $tag['title']
            ]);
        }
    }

    // прикрепить теги к новостям
    public function createNewsTags($news_tags) {
        foreach ($news_tags as $news_tag) {
            $kek = News::find($news_tag['news_id']);
            NewsTag::firstOrcreate([
                'news_id' => News::find($news_tag['news_id'])->id,
                'tag_id' => Tag::find($news_tag['tag_id'])->id,
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

    // создать пользователей
    public function createUsers($usersArray) {
        foreach ($usersArray as $user) {
            $createdUser = User::firstOrcreate([
                'surname' => $user['surname'],
                'name' => $user['name'],
                'patronymic' => $user['patronymic'],
                'phone_number' => $user['phone_number'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'role_id' => $user['role_id'],
            ]);
            if ($createdUser->role_id === User::ROLE_TEACHER) {
                Teacher::firstOrcreate([
                    'user_id' => $createdUser->id,
                    'post' => $user['post'],
                    'chair_id' => Chair::where('title', $user['chair'])->first()->id,
                ]);
            }
            if ($createdUser->role_id === User::ROLE_EMPLOYEE) {
                Employee::firstOrcreate([
                    'user_id' => $createdUser->id,
                    'chair_id' => Chair::where('title', $user['chair'])->first()->id,
                ]);
            }
        }
    }

    // создать учебную нагрузку
    public function createLessons($lessons) {
        foreach ($lessons as $lesson) {
            $teacher = null;
            if (isset($lesson['teacher'])) {
                $user = User::where('surname', $lesson['teacher'])->first();
                $teacher = $user->teacher;
            }
            $yearInput = explode('-', $lesson['year']);
            $year = Year::firstOrCreate([
                'start_year' => $yearInput[0],
                'end_year' => $yearInput[1],
            ]);
            Lesson::firstOrcreate([
                'discipline_id' => Discipline::where('title', $lesson['discipline'])->first()->id,
                'group_id' => Group::where('title', $lesson['group'])->first()->id,
                'teacher_id' => $teacher != null ? $teacher->id : null,
                'semester' => $lesson['semester'],
                'year_id' => $year->id,
            ]);
        }
    }

    // создать новости
    public function createPosts($posts) {
        foreach ($posts as $post) {
            $event = null;
            if (isset($post['event'])) {
                $eventInput = explode('-', $post['event']);
                $event = Event::firstOrCreate([
                    'start_date' => isset($eventInput[0]) ? $eventInput[0] : null,
                    'finish_date' => isset($eventInput[1]) ? $eventInput[1] : null,
                ])->id;
            }
            $news = News::firstOrCreate([
                'title' => $post['title'],
                'content' => $post['content'],
                'images' => json_encode($post['images']),
                'chair_id' => Chair::where('title', $post['chair'])->first()->id,
                'category_id' => Category::where('title', $post['category'])->first()->id,
                'is_slider_item' => $post['is_slider_item'],
                'preview' => $post['preview'],
                'event_id' => $event,
            ]);
        }
    }
}
