<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $employeeViews = [
            'employee.main.index',
            'employee.chair.edit',
            'employee.news.index',
            'employee.news.create',
            'employee.news.edit',
            'employee.schedule.index',
            'employee.schedule.group.edit',
            'employee.group.index',
            'employee.schedule.group.show'
        ];
        View::composer($employeeViews, function ($view) {
            $view->with(['roleTeacher' => User::ROLE_TEACHER]);
        });
    }
}
