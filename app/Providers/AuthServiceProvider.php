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
            if ($user->role->teacher_id != null) {
                return Response::allow();
            }
            return Response::deny();
        });

        Gate::define('create-task', function(User $user) {
            if ($user->role->teacher_id != null) {
                return Response::allow();
            }
            return Response::deny();
        });

        Gate::define('download-task', function(User $user, $mediaId) {
            // Если файл пытается скачать преподаватель
            if (isset($user->role->teacher_id)) {
                $teacher = $user->role->teacher;
                $media = $teacher->getMedia(Task::PATH)->where('id', $mediaId)->first();
                if (isset($media)) {
                    // Файл скачивает владелец
                    return Response::allow();
                }
                // Файл скачивает не владелец
                return Response::deny();
            } 
            // Если файл пытается скачать студент
            if (isset($user->role->student_id)) {
                $task_url = Media::find($mediaId)->getUrl();
                $task = Task::where('task', '=', $task_url)->firstOrFail();
                $student = $user->role->student;
                if ($student->group_id == $task->group_id) {
                    // Файл разрешено скачивать только студентам той группы,
                    // для которой было опубликовано задание
                    return Response::allow();
                }
                return Response::deny();
            }
            return Response::deny();
        });
    }
}
