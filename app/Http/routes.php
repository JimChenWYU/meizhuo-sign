<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'SignController@index');
Route::get('sign', 'SignController@index');

Route::get('admin', 'managerController@index');
Route::post('admin', 'managerController@login');

Route::group([ 'middleware' => [ 'jwt.refresh', 'jwt.auth' ] ], function () {

    Route::post('admin/test', 'managerController@testToken');
});



Route::post('sign', 'SignController@sign');
