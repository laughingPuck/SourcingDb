<?php

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
    return redirect('/admin');
});
//Route::get('/', 'SessionsController@create');
//Route::post('login', 'SessionsController@store')->name('login');
//Route::get('/show', 'SessionsController@show')->name('show');
////Route::get('/help', 'StaticPagesController@help')->name('help');
//Route::delete('logout', 'SessionsController@destroy')->name('logout');
//
//Route::get('/stickwcup/show', 'StickWCupController@show')->name('stickwcup.show');
//
//Route::resource('users', 'UsersController');

/*
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');*/

//php artisan route:list