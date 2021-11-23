<?php

namespace App\Providers;

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

        Gate::define('index-task', function(User $user) {
            return $user->role_id == User::ROLE_TEACHER ? Response::allow() : Response::deny();
        });

        Gate::define('create-task', function(User $user) {
            return $user->role_id == User::ROLE_TEACHER ? Response::allow() : Response::deny();
        });

        Gate::define('download-task', function(User $user, $mediaId) {
            // Если файл пытается скачать преподаватель
            if ($user->role_id == User::ROLE_TEACHER) {
                $teacher = $user->teacher;
                $media = $teacher->getMedia(Task::PATH)->where('id', $mediaId)->first();
                // Файл разрешено скачивать только его владельцу
                return isset($media) ? Response::allow() : Response::deny();
            } 
            // Если файл пытается скачать студент
            if ($user->role_id == User::ROLE_STUDENT) {
                $task_url = Media::find($mediaId)->getUrl();
                $task = Task::where('task', '=', $task_url)->firstOrFail();
                $student = $user->student;
                // Файл разрешено скачивать только студентам той группы,
                // для которой было опубликовано задание
                return $student->group_id == $task->group_id ? Response::allow() : Response::deny();
            }
            return Response::deny();
        });

        Gate::define('show-task', function(User $user, $task) {
            return $user->role_id == User::ROLE_TEACHER &&
            $task->teacher_id == $user->teacher->id ?
            Response::allow() : Response::deny();
        });
    }
}
