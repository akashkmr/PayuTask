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

//Route::get('/', function () {
//    return view('auth.login');
//});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => 'auth'], function () {
    
Route::get('/', 'CronjobController@login');
Route::get('/cronjob/create', 'CronjobController@create');
Route::get('/cronjob/download', 'CronjobController@show');
Route::post('/cronjob', 'CronjobController@store');

});

//Route::get('/', 'Admin\CronjobController@login');
//Route::get('/home', 'HomeController@index')->name('home');

//});

