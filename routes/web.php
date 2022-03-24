<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'Main'], function () {
    Route::get('/', 'IndexController')->name('main');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'IndexController');
    });
    Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
        Route::get('/', 'GroupController@index')->name('admin.group.index');
        Route::get('/create', 'GroupController@create')->name('admin.group.create');
        Route::post('/store', 'GroupController@store')->name('admin.group.store');
        Route::get('/{group}', 'GroupController@show')->name('admin.group.show');
        Route::get('/{group}/edit', 'GroupController@edit')->name('admin.group.edit');
        Route::patch('/{group}', 'GroupController@update')->name('admin.group.update');
        Route::delete('/{group}', 'GroupController@delete')->name('admin.group.delete');
    });

    Route::group(['namespace' => 'Lesson', 'prefix' => 'lessons'], function () {
        Route::get('/', 'LessonController@index')->name('admin.lesson.index');
        Route::get('/create', 'LessonController@create')->name('admin.lesson.create');
        Route::post('/store', 'LessonController@store')->name('admin.lesson.store');
        Route::get('/{lesson}', 'LessonController@show')->name('admin.lesson.show');
        Route::get('/{lesson}/edit', 'LessonController@edit')->name('admin.lesson.edit');
        Route::patch('/{lesson}', 'LessonController@update')->name('admin.lesson.update');
        Route::delete('/{lesson}', 'LessonController@delete')->name('admin.lesson.delete');
    });

    Route::group(['namespace' => 'Discipline', 'prefix' => 'disciplines'], function () {
        Route::get('/', 'DisciplineController@index')->name('admin.discipline.index');
        Route::get('/create', 'DisciplineController@create')->name('admin.discipline.create');
        Route::post('/store', 'DisciplineController@store')->name('admin.discipline.store');
        Route::get('/{discipline}', 'DisciplineController@show')->name('admin.discipline.show');
        Route::get('/{discipline}/edit', 'DisciplineController@edit')->name('admin.discipline.edit');
        Route::patch('/{discipline}', 'DisciplineController@update')->name('admin.discipline.update');
        Route::delete('/{discipline}', 'DisciplineController@delete')->name('admin.discipline.delete');
    });

    Route::group(['namespace' => 'User', 'prefix' => 'users'], function () {
        Route::get('/search', 'UserController@search')->name('admin.user.search');
        Route::get('/', 'UserController@index')->name('admin.user.index');
        Route::get('/create', 'UserController@create')->name('admin.user.create');
        Route::post('/store', 'UserController@store')->name('admin.user.store');
        Route::get('/{user}', 'UserController@show')->name('admin.user.show');
        Route::get('/{user}/edit', 'UserController@edit')->name('admin.user.edit');
        Route::patch('/{user}', 'UserController@update')->name('admin.user.update');
        Route::delete('/{user}', 'UserController@delete')->name('admin.user.delete');
    });

    Route::group(['namespace' => 'Chair', 'prefix' => 'chairs'], function () {
        Route::get('/', 'ChairController@index')->name('admin.chair.index');
        Route::get('/create', 'ChairController@create')->name('admin.chair.create');
        Route::post('/store', 'ChairController@store')->name('admin.chair.store');
        Route::get('/{chair}', 'ChairController@show')->name('admin.chair.show');
        Route::get('/{chair}/edit', 'ChairController@edit')->name('admin.chair.edit');
        Route::patch('/{chair}', 'ChairController@update')->name('admin.chair.update');
        Route::delete('/{chair}', 'ChairController@delete')->name('admin.chair.delete');
    });

    Route::group(['namespace' => 'Schedule', 'prefix' => 'schedules'], function () {
        Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
            Route::get('{group}/create', 'GroupController@create')->name('admin.schedule.group.create');
            Route::post('/store', 'GroupController@store')->name('admin.schedule.group.store');
            Route::get('{group}', 'GroupController@show')->name('admin.schedule.group.show');
            Route::get('pair={schedule}/edit', 'GroupController@edit')->name('admin.schedule.group.edit');
            Route::patch('{schedule}', 'GroupController@update')->name('admin.schedule.group.update');
        });
        Route::get('/', 'ScheduleController@index')->name('admin.schedule.index');
    });
});

Route::group(['namespace' => 'Employee', 'prefix' => 'employee', 'middleware' => ['auth', 'employee', 'verified']], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'IndexController')->name('employee.main.index');
    });
    Route::group(['namespace' => 'Chair', 'prefix' => 'chair'], function () {
        Route::get('{chair}/edit', 'ChairController@edit')->name('employee.chair.edit');
        Route::patch('{chair}', 'ChairController@update')->name('employee.chair.update');
    });

    Route::group(['namespace' => 'News', 'prefix' => 'news'], function () {
        Route::get('/', 'NewsController@index')->name('employee.news.index');
        Route::get('/create', 'NewsController@create')->name('employee.news.create');
        Route::post('/store', 'NewsController@store')->name('employee.news.store');
        Route::get('/{news}', 'NewsController@show')->name('employee.news.show');
        Route::get('/{news}/edit', 'NewsController@edit')->name('employee.news.edit');
        Route::patch('/{news}', 'NewsController@update')->name('employee.news.update');
        Route::delete('/{news}', 'NewsController@delete')->name('employee.news.delete');
    });

    Route::group(['namespace' => 'Schedule', 'prefix' => 'schedules'], function () {
        Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
            Route::get('{group}', 'GroupController@show')->name('employee.schedule.group.show');
            Route::get('pair={schedule}/edit', 'GroupController@edit')->name('employee.schedule.group.edit');
            Route::patch('{schedule}', 'GroupController@update')->name('employee.schedule.group.update');
        });
        Route::get('/', 'ScheduleController@index')->name('employee.schedule.index');
        Route::post('/import', 'ScheduleController@import')->name('employee.schedule.import');
    });
});

Route::group(['namespace' => 'CertAuthority', 'prefix' => 'ca', 'middleware' => ['auth', 'ca', 'verified']], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'IndexController')->name('ca.main.index');
    });
    Route::group(['namespace' => 'CertApp', 'prefix' => 'cert_apps'], function () {
        Route::get('/', 'CertAppController@index')->name('ca.cert_app.index');
        Route::get('/{certApp}', 'CertAppController@accept')->name('ca.cert_app.accept');
        Route::post('/store', 'CertAppController@store')->name('ca.cert_app.store');
    });
});

Route::group(['namespace' => 'Dekanat', 'prefix' => 'dekanat', 'middleware' => ['auth', 'dekanat', 'verified']], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'MainController@index')->name('dekanat.main.index');
    });
    Route::group(['namespace' => 'ExamSheet', 'prefix' => 'exam_sheets'], function () {
        Route::get('/', 'ExamSheetController@index')->name('dekanat.exam_sheet.index');
        Route::post('/{sheet}', 'ExamSheetController@issue')->name('dekanat.exam_sheet.issue');
    });

    Route::group(['namespace' => 'Statement', 'prefix' => 'statements'], function () {
        Route::get('/', 'StatementController@index')->name('dekanat.statement.index');
        Route::get('/{statement}', 'StatementController@show')->name('dekanat.statement.show');
        Route::get('/{statement}/export', 'StatementController@export')->name('dekanat.statement.export');
        Route::get('/{statement}/download', 'StatementController@download')->name('dekanat.statement.download');
        Route::get('/getYears/{id}', 'StatementController@getYears');
        Route::get('/create/{group}', 'StatementController@create')->name('dekanat.statement.create');
        Route::get('/create/getDisciplines/{groupId}/{yearId}', 'StatementController@getDisciplines');
        Route::post('/', 'StatementController@store')->name('dekanat.statement.store');
    });
});

Route::group(['namespace' => 'Personal', 'prefix' => 'personal', 'middleware' => ['auth', 'verified']], function () {
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings'], function () {
        Route::get('/{modelId}/{mediaId}/{filename}', 'SettingsController@showImage')->name('personal.settings.showImage');
    });
});

Route::group(['namespace' => 'Personal', 'prefix' => 'personal', 'middleware' => ['auth', '2fa', 'verified', 'personal']], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'MainController@index')->name('personal.main.index');
        Route::get('/schedule', 'MainController@showSchedule')->name('personal.main.schedule');
    });
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings'], function () {
        Route::get('/', 'SettingsController@edit')->name('personal.settings.edit');
        Route::patch('/{user}', 'SettingsController@updateMain')->name('personal.settings.updateMain');
        Route::patch('/{user}/password', 'SettingsController@updatePassword')->name('personal.settings.updatePassword');
        Route::patch('/{user}/security', 'SettingsController@changeSecurity')->name('personal.settings.changeSecurity');
    });
    Route::group(['namespace' => 'Application', 'prefix' => 'applications'], function () {
        Route::get('/', 'ApplicationController@index')->name('personal.application.index');
        Route::get('/{application}/accept', 'ApplicationController@accept')->name('personal.application.accept');
        Route::get('/{application}/reject', 'ApplicationController@reject')->name('personal.application.reject');
    });
    Route::group(['namespace' => 'Task', 'prefix' => 'tasks'], function () {
        Route::get('/', 'TaskController@index')->name('personal.task.index');
        Route::get('/create', 'TaskController@create')->name('personal.task.create');
        Route::post('/store', 'TaskController@store')->name('personal.task.store');
        Route::get('/{modelId}/{mediaId}/{filename}', 'TaskController@download')->name('personal.task.download');
        Route::get('/{task}', 'TaskController@show')->name('personal.task.show');
        Route::patch('/{task}', 'TaskController@complete')->name('personal.task.complete');
    });
    Route::group(['namespace' => 'Homework', 'prefix' => 'homework'], function () {
        Route::get('/', 'HomeworkController@index')->name('personal.homework.index');
        Route::get('/task={task}', 'HomeworkController@create')->name('personal.homework.create');
        Route::post('/store', 'HomeworkController@store')->name('personal.homework.store');
        Route::get('/{modelId}/{mediaId}/{filename}', 'HomeworkController@download')->name('personal.homework.download');
        Route::get('/{homework}', 'HomeworkController@show')->name('personal.homework.show');
        Route::patch('/{homework}/feedback', 'HomeworkController@feedback')->name('personal.homework.feedback');
        Route::delete('/{homework}', 'HomeworkController@delete')->name('personal.homework.delete');
    });
    Route::group(['namespace' => 'News', 'prefix' => 'news'], function () {
        Route::get('/', 'NewsController@index')->name('personal.news.index');
        Route::get('/create', 'NewsController@create')->name('personal.news.create');
        Route::post('/store', 'NewsController@store')->name('personal.news.store');
        Route::get('/{modelId}/{mediaId}/{filename}', 'NewsController@showImage')->name('personal.news.showImage');
        Route::get('/{news}/edit', 'NewsController@edit')->name('personal.news.edit');
        Route::patch('/{news}', 'NewsController@update')->name('personal.news.update');
        Route::delete('/{news}', 'NewsController@delete')->name('personal.news.delete');
    });
    Route::group(['namespace' => 'Cert', 'prefix' => 'cert'], function () {
        Route::get('/create', 'CertController@create')->name('personal.cert.create');
        Route::post('/store', 'CertController@store')->name('personal.cert.store');
        Route::get('/', 'CertController@index')->name('personal.cert.index');
    });
    Route::group(['namespace' => 'Statement', 'prefix' => 'statements'], function () {
        Route::get('/', 'StatementController@index')->name('personal.statement.index');
        Route::get('/{statement}', 'StatementController@show')->name('personal.statement.show');
        Route::post('/saveData', 'StatementController@saveData')->name('personal.statement.saveData');
        Route::post('/{statement}', 'StatementController@signStatement')->name('personal.statement.signStatement');
        Route::get('/getYears/{id}', 'StatementController@getYears');
        Route::get('/getCompletedSheets/{statement}', 'StatementController@getCompletedSheets');
        Route::get('/getEvalTypes/all', 'StatementController@getEvalTypes')->name('personal.statement.getEvalTypes');
    });
    Route::group(['namespace' => 'ExamSheet', 'prefix' => 'exam_sheets'], function () {
        Route::get('/', 'ExamSheetController@index')->name('personal.exam_sheet.index');
        Route::get('/{sheet}', 'ExamSheetController@show')->name('personal.exam_sheet.show');
        Route::post('/', 'ExamSheetController@store')->name('personal.exam_sheet.store');
        Route::patch('/{examSheet}', 'ExamSheetController@sign')->name('personal.exam_sheet.sign');
    });
});

Route::get('two-factor-auth', [TwoFactorAuthController::class, 'index'])->name('2fa.index');
Route::post('two-factor-auth', [TwoFactorAuthController::class, 'store'])->name('2fa.store');
Route::get('two-factor-auth/resent', [TwoFactorAuthController::class, 'resend'])->name('2fa.resend');

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
