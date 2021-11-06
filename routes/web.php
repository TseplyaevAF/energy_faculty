<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', 'IndexController');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/', 'IndexController');
    });
    Route::group(['namespace' => 'Group', 'prefix' => 'groups'], function () {
        Route::get('/cart', 'CartController@index')->name('admin.group.cart.index');
        Route::get('/cart/{newsId}', 'CartController@show')->name('admin.group.cart.show');
        Route::get('/cart/{newsId}/restore', 'CartController@restore')->name('admin.group.cart.restore');
        Route::delete('/cart/{newsId}', 'CartController@delete')->name('admin.group.cart.delete');

        Route::group(['namespace' => 'News', 'prefix' => 'news'], function () {
            Route::get('/cart', 'CartController@index')->name('admin.group.news.cart.index');
            Route::get('/cart/{newsId}', 'CartController@show')->name('admin.group.news.cart.show');
            Route::get('/cart/{newsId}/restore', 'CartController@restore')->name('admin.group.news.cart.restore');
            Route::delete('/cart/{newsId}', 'CartController@delete')->name('admin.group.news.cart.delete');

            Route::get('/', 'NewsController@index')->name('admin.group.news.index');
            Route::get('/create', 'NewsController@create')->name('admin.group.news.create');
            Route::post('/store', 'NewsController@store')->name('admin.group.news.store');
            Route::get('/{news}', 'NewsController@show')->name('admin.group.news.show');
            Route::get('/{news}/edit', 'NewsController@edit')->name('admin.group.news.edit');
            Route::patch('/{news}', 'NewsController@update')->name('admin.group.news.update');
            Route::delete('/{news}', 'NewsController@delete')->name('admin.group.news.delete');
        });
        Route::get('/', 'GroupController@index')->name('admin.group.index');
        Route::get('/create', 'GroupController@create')->name('admin.group.create');
        Route::post('/store', 'GroupController@store')->name('admin.group.store');
        Route::get('/{group}', 'GroupController@show')->name('admin.group.show');
        Route::get('/{group}/edit', 'GroupController@edit')->name('admin.group.edit');
        Route::patch('/{group}', 'GroupController@update')->name('admin.group.update');
        Route::delete('/{group}', 'GroupController@delete')->name('admin.group.delete');
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
        Route::get('/', 'UserController@index')->name('admin.user.index');
        Route::get('/create', 'UserController@create')->name('admin.user.create');
        Route::post('/store', 'UserController@store')->name('admin.user.store');
        Route::get('/{user}', 'UserController@show')->name('admin.user.show');
        Route::get('/{user}/edit', 'UserController@edit')->name('admin.user.edit');
        Route::patch('/{user}', 'UserController@update')->name('admin.user.update');
        Route::delete('/{user}', 'UserController@delete')->name('admin.user.delete');
        
        Route::get('/query', 'UserController@query')->name('admin.user.query');
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
