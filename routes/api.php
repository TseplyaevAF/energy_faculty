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
    'categories' => 'Api\CategoryController'
]);

Route::group(['middleware' => ['auth:web', 'personal']], function () {
    Route::apiResource('statements', 'Api\StatementController');
    Route::get('/control-forms', 'Api\StatementController@getControlForms');
});
