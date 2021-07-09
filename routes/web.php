<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'can:user-views'])->group(function(){

    Route::get('/user/dashboard', 'TaskController@dashboard')->name('user.dashboard');
    Route::get('/user/tasks', 'TaskController@index')->name('user.tasks.index');
    Route::get('/user/tasks/{task}', 'TaskController@show')->name('user.tasks.show');
    Route::get('/user/tasks/create', 'TaskController@create')->name('user.tasks.create');
    Route::post('/user/tasks', 'TaskController@store')->name('user.tasks.store');
    Route::delete('/user/tasks/{task}/delete', 'TaskController@destroy')->name('user.tasks.destroy');

    Route::get('/user/tasks/{task}/edit', 'TaskController@edit')->name('user.tasks.edit');
    Route::post('/user/tasks/{task}', 'TaskController@update')->name('user.tasks.update');

});

