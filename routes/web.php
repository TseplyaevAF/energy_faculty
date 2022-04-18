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
    Route::resource('groups', 'Group\GroupController', ['names' => 'admin.group']);
    Route::resource('lessons', 'Lesson\LessonController', ['names' => 'admin.lesson']);
    Route::resource('disciplines', 'Discipline\DisciplineController', ['names' => 'admin.discipline']);
    Route::resource('chairs', 'Chair\ChairController', ['names' => 'admin.chair']);

    Route::resource('users', 'User\UserController', ['names' => 'admin.user']);
    Route::get('users/user/search', 'User\UserController@search')->name('admin.user.search');
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
    Route::resource('chairs', 'Chair\ChairController', ['names' => 'employee.chair']);
    Route::resource('news', 'News\NewsController', ['names' => 'employee.news']);

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
    Route::resource('cert_apps', 'CertApp\CertAppController', ['names' => 'ca.cert_app']);
    Route::get('/cert_apps/{certApp}', 'CertApp\CertAppController@accept')->name('ca.cert_app.accept');
});

Route::group(['namespace' => 'Dekanat', 'prefix' => 'dekanat', 'middleware' => ['auth', 'dekanat', 'verified']], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'MainController@index')->name('dekanat.main.index');
    });
    Route::group(['namespace' => 'ExamSheet', 'prefix' => 'exam_sheets'], function () {
        Route::get('/', 'ExamSheetController@index')->name('dekanat.exam_sheet.index');
        Route::post('/{sheet}', 'ExamSheetController@issue')->name('dekanat.exam_sheet.issue');
    });
    Route::resource('statements', 'Statement\StatementController', ['names' => 'dekanat.statement'])
        ->only('index', 'show', 'store');

    Route::group(['namespace' => 'Statement', 'prefix' => 'statements'], function () {
        Route::get('/create/{group}', 'StatementController@create')->name('dekanat.statement.create');
        Route::get('/{statement}/export', 'StatementController@export')->name('dekanat.statement.export');
        Route::get('/{statement}/download', 'StatementController@download')->name('dekanat.statement.download');
        Route::get('/getYears/{id}', 'StatementController@getYears');
        Route::get('/create/getDisciplines/{groupId}/{yearId}', 'StatementController@getDisciplines');
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

    Route::resource('tasks', 'Task\TaskController', ['names' => 'personal.task'])->only(['index', 'store']);
    Route::get('tasks/{modelId}/{mediaId}/{filename}', 'Task\TaskController@download')->name('personal.task.download');
    Route::patch('tasks/{task}', 'Task\TaskController@complete')->name('personal.task.complete');
    Route::get('tasks/get-groups/{discipline}', 'Task\TaskController@getGroups');
    Route::get('tasks/get/semesters/{discipline}/{group}', 'Task\TaskController@getSemesters');
    Route::get('tasks/get-tasks', 'Task\TaskController@getTasks');

    Route::resource('homework', 'Homework\HomeworkController', ['names' => 'personal.homework'])
        ->only('index', 'store', 'destroy');
    Route::get('homework/task={task}', 'Homework\HomeworkController@create')->name('personal.homework.create');
    Route::get('/homework/{modelId}/{mediaId}/{filename}', 'Homework\HomeworkController@download')->name('personal.homework.download');
    Route::patch('/homework/{homework}/feedback', 'Homework\HomeworkController@feedback')->name('personal.homework.feedback');

    Route::resource('news', 'News\NewsController', ['names' => 'personal.news']);
    Route::get('/news/{modelId}/{mediaId}/{filename}', 'NewsController@showImage')
        ->name('personal.news.showImage');

    Route::resource('cert', 'Cert\CertController', ['names' => 'personal.cert']);

    Route::resource('statements', 'Statement\StatementController', ['names' => 'personal.statement'])
        ->only('index', 'show');

    Route::group(['namespace' => 'Statement', 'prefix' => 'statements'], function () {
        Route::post('/saveData', 'StatementController@saveData')->name('personal.statement.saveData');
        Route::post('/{statement}', 'StatementController@signStatement')->name('personal.statement.signStatement');
        Route::get('/getYears/{id}', 'StatementController@getYears');
        Route::get('/getCompletedSheets/{statement}', 'StatementController@getCompletedSheets');
        Route::get('/getEvalTypes/all', 'StatementController@getEvalTypes')->name('personal.statement.getEvalTypes');
    });

    Route::resource('exam_sheets', 'ExamSheet\ExamSheetController', ['names' => 'personal.exam_sheet']);
    Route::patch('/{examSheet}', 'ExamSheetController@sign')->name('personal.exam_sheet.sign');

    Route::group(['namespace' => 'Mark', 'prefix' => 'marks'], function () {
        Route::get('/', 'MarkController@index')->name('personal.mark.index');
        Route::get('/statements-report/{statement}', 'MarkController@getStatementInfo')->name('personal.mark.getStatementInfo');
        Route::get('/statements-download/{statement}', 'MarkController@statementDownload')->name('personal.mark.statementDownload');
        Route::get('/semesters-report/{group}/{semester}', 'MarkController@getSemesters')->name('personal.mark.getSemesters');
        Route::get('/group-students/{group}', 'MarkController@getStudents');
        Route::get('/set-new-headman/{group}/{headmanId}', 'MarkController@setNewHeadman');
        Route::get('/get-disciplines/{group}', 'MarkController@getDisciplines');
        Route::get('/get-years/{group}/{discipline}', 'MarkController@getYears');
        Route::get('/get-tasks', 'MarkController@getTasks');
    });
});

Route::get('two-factor-auth', [TwoFactorAuthController::class, 'index'])->name('2fa.index');
Route::post('two-factor-auth', [TwoFactorAuthController::class, 'store'])->name('2fa.store');
Route::get('two-factor-auth/resent', [TwoFactorAuthController::class, 'resend'])->name('2fa.resend');

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
