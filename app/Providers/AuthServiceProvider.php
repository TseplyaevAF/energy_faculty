<?php

namespace App\Providers;

use App\Models\Cert\CertApp;
use App\Models\Lesson;
use App\Models\Student\Headman;
use App\Models\Student\Homework;
use App\Models\Teacher\Task;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\Models\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /// SCHEDULE
        Gate::define('edit-schedule', function (User $user) {
            return $user->role_id == User::ROLE_TEACHER ? Response::allow() : Response::deny();
        });

        /// TASKS
        Gate::define('index-task', function (User $user) {
            return $user->role_id == User::ROLE_TEACHER ? Response::allow() : Response::deny();
        });

        Gate::define('download-task', function (User $user, $task) {
            // Если файл пытается скачать преподаватель
            if ($user->role_id == User::ROLE_TEACHER) {
                // Файл разрешено скачивать только его владельцу
                return $task->lesson->teacher_id == $user->teacher->id ? Response::allow() : Response::deny();
            }
            // Если файл пытается скачать студент
            if ($user->role_id == User::ROLE_STUDENT) {
                $student = $user->student;
                // Файл разрешено скачивать только студентам той группы,
                // для которой было опубликовано задание
                return $student->group_id == $task->lesson->group_id ? Response::allow() : Response::deny();
            }
            return Response::deny();
        });

        Gate::define('show-task', function (User $user, $task) {
            return $user->role_id == User::ROLE_TEACHER &&
                $task->lesson->teacher_id == $user->teacher->id ?
                Response::allow() : Response::deny();
        });


        /// HOMEWORK
        Gate::define('index-homework', function (User $user) {
            return $user->role_id == User::ROLE_STUDENT ? Response::allow() : Response::deny();
        });

        Gate::define('download-homework', function (User $user, $homework) {
            // Если файл пытается скачать преподаватель
            if ($user->role_id == User::ROLE_TEACHER) {
                // Файл разрешено скачивать только тому, кто опубликовал задание
                return $user->teacher->id == $homework->task->lesson->teacher_id ? Response::allow() : Response::deny();
            }
            // Если файл пытается скачать студент
            if ($user->role_id == User::ROLE_STUDENT) {
                $student = $user->student;
                // Файл разрешено скачивать только его владельцу
                return $student->group_id == $homework->task->lesson->group_id ? Response::allow() : Response::deny();
            }
            return Response::deny();
        });

        Gate::define('feedback-homework', function (User $user, $homework) {
            if ($user->role_id == User::ROLE_TEACHER) {
                return $homework->task->lesson->teacher_id == $user->teacher->id ?
                    Response::allow() : Response::deny();
            }
            return Response::deny();
        });


        /// APPLICATIONS
        Gate::define('index-application', function (User $user) {
            return isset($user->student->headman) ? Response::allow() : Response::deny();
        });


        /// GROUP NEWS
        Gate::define('create-group-news', function (User $user) {
            return isset($user->student->headman) ? Response::allow() : Response::deny();
        });

        Gate::define('edit-group-news', function (User $user, $post) {
            return $user->student->group_id ==  $post->group_id? Response::allow() : Response::deny();
        });


        /// CERT APP
        Gate::define('create-cert-app', function ($teacher) {
            $teacher = CertApp::where('teacher_id', $teacher->id)->first();
            return !isset($teacher) ? Response::allow() : Response::deny();
        });
    }
}
