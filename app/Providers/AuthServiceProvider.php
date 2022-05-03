<?php

namespace App\Providers;

use App\Models\Cert\CertApp;
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

        Gate::define('isTeacher', function (User $user) {
            return $user->role_id == User::ROLE_TEACHER ? Response::allow() : Response::deny();
        });

        Gate::define('isStudent', function (User $user) {
            return $user->role_id == User::ROLE_STUDENT ? Response::allow() : Response::deny();
        });

        Gate::define('isHeadman', function (User $user) {
            return $user->student->group->headman == $user->student->id ? Response::allow() : Response::deny();
        });

        Gate::define('isCurator', function (User $user) {
            return count($user->teacher->myGroups) != 0;
        });

        Gate::define('isCuratorGroup', function (User $user, $group) {
            $groups = $user->teacher->myGroups;
            if ($groups->count() !== 0) {
                return in_array($group->id, $groups->pluck('id')->toArray());
            }
            return false;
        });

        Gate::define('isStudentGroup', function (User $user, $group) {
            if (Gate::allows('isStudent')) {
                return auth()->user()->student->group->id == $group->id;
            }
            return false;
        });

        /// TASKS
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


        /// GROUP NEWS
        Gate::define('edit-group-news', function (User $user, $post) {
            return $user->id == $post->user_id ? Response::allow() : Response::deny();
        });

        Gate::define('create-group-news', function (User $user, $group) {
            if (Gate::allows('isStudent')) {
                Gate::authorize('isHeadman');
                return Response::allow();
            }
            if (Gate::allows('isTeacher')) {
                Gate::authorize('isCuratorGroup', $group);
                return Response::allow();
            }
            return Response::deny();
        });


        /// CERT APP
        Gate::define('create-cert-app', function (User $user, $teacher) {
            $teacher = CertApp::where('teacher_id', $teacher->id)->first();
            return !isset($teacher) ? Response::allow() : Response::deny();
        });


        /// EXAM SHEETS
        Gate::define('show-exam-sheet', function (User $user, $sheet) {
            return
                $sheet->individual->statement->lesson->teacher->id == $user->teacher->id
                    && $sheet->dekan_signature != null
                    ? Response::allow() : Response::deny();
        });
    }
}
