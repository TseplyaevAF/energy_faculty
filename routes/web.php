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

Route::get('/', 'Main\IndexController')->name('main');

// ---ADMIN ROUTES---
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::get('/', 'Main\IndexController');
    Route::resource('groups', 'Group\GroupController', ['names' => 'admin.group']);
    Route::resource('disciplines', 'Discipline\DisciplineController', ['names' => 'admin.discipline']);
    Route::resource('chairs', 'Chair\ChairController', ['names' => 'admin.chair']);

    Route::group(['namespace' => 'Lesson', 'prefix' => 'lessons'], function () {
        Route::get('/', 'LessonController@index')->name('admin.lesson.index');
        Route::get('/get-years', 'LessonController@getYears');
        Route::post('/add-year', 'LessonController@addYear');
        Route::get('/{chair}/{year}', 'LessonController@getChairLoad')->name('admin.lesson.get-chair-load');
        Route::get('/{chair}/{year}/load-teachers/{lesson}', 'LessonController@loadTeachers');
        Route::patch('/{chair}/{year}/{lesson}', 'LessonController@update');
        Route::get('/{chair}/{year}/create', 'LessonController@create')->name('admin.lesson.create');
        Route::post('/{chair}/{year}', 'LessonController@store')->name('admin.lesson.store');
    });

    Route::resource('users', 'User\UserController', ['names' => 'admin.user'])
        ->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    Route::get('users/user/search', 'User\UserController@search')->name('admin.user.search');
    Route::get('users/students-template-export', 'User\UserController@studentsExport');
    Route::post('users/students-import', 'User\UserController@studentsImport');
});

// ---EMPLOYEE ROUTES---
Route::group(['namespace' => 'Employee', 'prefix' => 'employee', 'middleware' => ['auth', 'employee']], function () {
    Route::get('/', 'Main\IndexController')->name('employee.main.index');
    Route::resource('chairs', 'Chair\ChairController', ['names' => 'employee.chair']);
    Route::resource('news', 'News\NewsController', ['names' => 'employee.news'])
        ->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::group(['namespace' => 'News', 'prefix' => 'news'], function () {
        Route::get('/create/{category}/{olimpType}/{news}', 'NewsController@create');
        Route::patch('/add-to-slider/{news}', 'NewsController@addToSlider');
    });

    Route::group(['namespace' => 'Olimp', 'prefix' => 'olimps'], function () {
        Route::get('/create-olimp', 'OlimpController@createOlimp');
        Route::get('/get-olimp-types/{category}', 'OlimpController@getOlimpTypes');
        Route::post('/store-olimp-type', 'OlimpController@storeOlimpType');
    });

    Route::group(['namespace' => 'Schedule', 'prefix' => 'schedules'], function () {
        Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
            Route::get('{group}', 'GroupController@show')->name('employee.schedule.group.show');
            Route::get('pair={schedule}/edit', 'GroupController@edit')->name('employee.schedule.group.edit');
            Route::patch('{schedule}', 'GroupController@update')->name('employee.schedule.group.update');
        });
        Route::get('/', 'ScheduleController@index')->name('employee.schedule.index');
        Route::post('/import', 'ScheduleController@import')->name('employee.schedule.import');
        Route::get('/export', 'ScheduleController@exportTemplate')->name('employee.schedule.exportTemplate');
    });

    Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
        Route::get('/', 'GroupController@index')->name('employee.group.index');
        Route::get('/load-teachers/{chair}/{group}', 'GroupController@getTeachers');
        Route::patch('/set-new-curator/{group}', 'GroupController@setNewCurator');
    });
});

// ---CENTRE AUTHORITY ROUTES---
Route::group(['namespace' => 'CertAuthority', 'prefix' => 'ca', 'middleware' => ['auth', 'ca']], function () {
    Route::get('/', 'Main\IndexController')->name('ca.main.index');
    Route::resource('cert_apps', 'CertApp\CertAppController', ['names' => 'ca.cert_app'])
        ->only('index', 'store', 'destroy');
    Route::get('/cert_apps/{certApp}', 'CertApp\CertAppController@accept')->name('ca.cert_app.accept');
});

// ---DEKANAT ROUTES---
Route::group(['namespace' => 'Dekanat', 'prefix' => 'dekanat', 'middleware' => ['auth', 'dekanat']], function () {
    Route::get('/', 'Main\MainController@index')->name('dekanat.main.index');
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

// ---USER AVATAR ROUTE---
Route::group(['namespace' => 'Personal', 'prefix' => 'personal', 'middleware' => ['auth']], function () {
    Route::get('settings/{modelId}/{mediaId}/{filename}', 'Settings/SettingsController@showImage')->name('personal.settings.showImage');
});

// ---PERSONAL (STUDENT AND TEACHER) ROUTES---
Route::group(['namespace' => 'Personal', 'prefix' => 'personal', 'middleware' => ['auth', '2fa', 'personal']], function () {
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

    Route::resource('tasks', 'Task\TaskController', ['names' => 'personal.task'])
        ->only(['index', 'store', 'destroy']);
    Route::get('tasks/{modelId}/{mediaId}/{filename}', 'Task\TaskController@download')->name('personal.task.download');
    Route::patch('tasks/{task}', 'Task\TaskController@complete')->name('personal.task.complete');
    Route::get('tasks/get-tasks', 'Task\TaskController@getTasks');
    Route::get('tasks/load-homework/{homework}', 'Task\TaskController@loadHomework');
    Route::post('tasks/store-edu', 'Task\TaskController@storeEduMaterial')->name('personal.task.store-edu');
    Route::get('tasks/get-edu-materials', 'Task\TaskController@getEduMaterials');
    Route::get('tasks/load-edu/{eduMaterial}', 'Task\TaskController@loadEduMaterial');
    Route::get('tasks/create-lesson', 'Task\TaskController@createLesson');
    Route::patch('tasks/create/lesson', 'Task\TaskController@createTeacherLesson');
    Route::get('tasks/get-students-progress', 'Task\TaskController@getStudentsProgress');

    Route::post('student-progress', 'StudentProgress\StudentProgressController@import');
    Route::get('student-progress/download-students-template/{group}',
        'StudentProgress\StudentProgressController@downloadStudentsTemplate');
    Route::get('student-progress/get-month-types',
        'StudentProgress\StudentProgressController@getMonthTypes');

    Route::resource('homework', 'Homework\HomeworkController', ['names' => 'personal.homework'])
        ->only('index', 'store', 'destroy');
    Route::get('homework/get-tasks', 'Homework\HomeworkController@getTasks');
    Route::get('homework/load-homework/{homework}', 'Homework\HomeworkController@loadHomework');
    Route::get('homework/get-edu-materials', 'Homework\HomeworkController@getEduMaterials');
    Route::get('/homework/{modelId}/{mediaId}/{filename}', 'Homework\HomeworkController@download');
    Route::patch('/homework/{homework}/feedback', 'Homework\HomeworkController@feedback');

    Route::resource('news', 'News\NewsController', ['names' => 'personal.news'])
        ->only(['store', 'update', 'destroy']);
    Route::get('/news/{group}', 'News\NewsController@index')->name('personal.news.index');
    Route::get('/news', 'News\NewsController@showGroupsCurator')->name('personal.news.showGroupsCurator');
    Route::get('/news/{group}/create', 'News\NewsController@create')->name('personal.news.create');
    Route::get('/news/{group}/{news}/edit', 'News\NewsController@edit')->name('personal.news.edit');
    Route::get('/news/group/get-unread-posts-count', 'News\NewsController@getUnreadPostsCount')->name('personal.news.getUnreadPostsCount');
    Route::get('/news/group/show/read-post/{news}', 'News\NewsController@readPost');
    Route::get('/news/group/show/new-added-post/{post}', 'News\NewsController@showNewAddedPost');

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

    Route::resource('exam_sheets', 'ExamSheet\ExamSheetController', ['names' => 'personal.exam_sheet'])
        ->only('index', 'store');
    Route::patch('exam_sheets/{examSheet}', 'ExamSheet\ExamSheetController@sign')->name('personal.exam_sheet.sign');
    Route::get('exam_sheets/{sheet}', 'ExamSheet\ExamSheetController@show')->name('personal.exam_sheet.show');

    Route::group(['namespace' => 'Mark', 'prefix' => 'marks'], function () {
        Route::get('/', 'MarkController@index')->name('personal.mark.index');
        Route::get('/statements-report/{statement}', 'MarkController@getStatementInfo')->name('personal.mark.getStatementInfo');
        Route::get('/statements-download/{statement}', 'MarkController@statementDownload')->name('personal.mark.statementDownload');
        Route::get('/semesters-report/{group}/{semester}', 'MarkController@getSemesterStatements');
        Route::get('/download-semester-statements/{group}/{semester}', 'MarkController@downloadSemesterStatements');
        Route::get('/group-students/{group}', 'MarkController@getStudents');
        Route::get('/set-new-headman/{group}/{headmanId}', 'MarkController@setNewHeadman');
        Route::get('/get-tasks', 'MarkController@getTasks');
        Route::get('/get-student-progress/{month}', 'MarkController@getStudentProgress');
        Route::get('/get-parents-emails', 'MarkController@getParentsEmails');
        Route::post('/send-student-progress', 'MarkController@sendStudentProgress');
        Route::get('/get-parents-contacts/{student}', 'MarkController@getParentsContacts');
        Route::patch('/update-parents-contacts/{student}', 'MarkController@updateParentsContacts');
    });
});

// ---TWO FACTOR AUTH ROUTES---
Route::get('two-factor-auth', [TwoFactorAuthController::class, 'index'])->name('2fa.index');
Route::post('two-factor-auth', [TwoFactorAuthController::class, 'store'])->name('2fa.store');
Route::get('two-factor-auth/resent', [TwoFactorAuthController::class, 'resend'])->name('2fa.resend');

Auth::routes();
