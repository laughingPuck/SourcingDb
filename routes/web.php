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

Route::get('/', 'SessionsController@create');
Route::post('login', 'SessionsController@store')->name('login');
Route::get('/show', 'SessionsController@show')->name('show');
//Route::get('/help', 'StaticPagesController@help')->name('help');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

Route::resource('users', 'UsersController');

//php artisan route:list