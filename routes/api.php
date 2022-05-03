<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'news' => 'Api\NewsController',
    'schedules' => 'Api\ScheduleController',
    'tags' => 'Api\TagController',
    'categories' => 'Api\CategoryController'
]);

Route::group(['middleware' => ['auth:web']], function () {
    Route::apiResource('statements', 'Api\StatementController')
        ->only('index');
    Route::get('/statements/control-forms', 'Api\StatementController@getControlForms');
    Route::get('/lessons/get-semesters', 'Api\LessonController@getSemesters');
    Route::get('/lessons/get-disciplines', 'Api\LessonController@getDisciplines');
    Route::get('/lessons/get-groups', 'Api\LessonController@getGroups');
    Route::get('/lessons/get-years', 'Api\LessonController@getYears');
});
