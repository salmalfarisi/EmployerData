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

Route::get('/', 'UserController@index')->name('user.index');
Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::post('login', 'UserController@store')->name('login');
	Route::get('/logout', 'UserController@logout')->name('logout')->middleware('token');
});

Route::group(['prefix' => 'karyawan', 'as' => 'karyawan.', 'middleware' => 'token'], function () {
	Route::get('/index', 'EmployerController@index')->name('index');
	Route::get('/create', 'EmployerController@create')->name('create');
	Route::post('/store', 'EmployerController@store')->name('store');
	Route::get('/detail/{id}', 'EmployerController@show')->name('show');
	Route::get('/edit/{id}', 'EmployerController@edit')->name('edit');
	Route::post('/update/{id}', 'EmployerController@update')->name('update');
	Route::get('/delete/{id}', 'EmployerController@destroy')->name('delete');
});
