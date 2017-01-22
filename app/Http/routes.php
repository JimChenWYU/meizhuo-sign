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
Route::get('user', 'SignController@index');

Route::get('admin', 'managerController@index');
Route::get('admin/login', 'managerController@index');
Route::post('admin', 'managerController@login');
Route::post('admin', 'managerController@login');
Route::get('admin/show', 'managerController@show');

Route::group([ 'middleware' => [ 'jwt.auth' ] ], function () {

    Route::post('admin/test', 'managerController@testToken');
    Route::get('admin/signer', 'managerController@getSigners');
    Route::get('admin/signer/{id}', 'managerController@getSigner');

});



Route::post('user', 'SignController@sign');

Route::get('test', function () {
   return view('index', [
       'title' => 'test',
       'type' => '__TEST__'
   ]);
});
