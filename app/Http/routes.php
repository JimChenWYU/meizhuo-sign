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
Route::post('user', 'SignController@sign');

Route::get('admin', 'ManagerController@index');
Route::get('admin/login', 'ManagerController@index');
Route::post('admin', 'ManagerController@login');
Route::get('admin/show', 'ManagerController@show');
Route::get('admin/show/{department}', 'ManagerController@show');
Route::get('admin/show/{department}/{id}', 'ManagerController@show');

Route::group([ 'middleware' => [ 'jwt.auth' ] ], function () {

    Route::get('testToken', 'ManagerController@testToken');
    Route::get('admin/signers', 'ManagerController@getSigners');
    Route::get('admin/signer', 'ManagerController@getSigner');
    Route::get('admin/permission', 'ManagerController@permission');

});

Route::get('admin/department', 'ManagerController@setDepartmentPage');

/**面试官登录或退出**/
Route::post('admin/department', 'ManagerController@setDepartment');
Route::delete('admin/department', 'ManagerController@outDepartment');

/**结束面试通知**/
Route::put('admin/department', 'ManagerController@EndingInterview');

Route::get('admin/sign', 'ManagerController@enQueue');
Route::get('admin/queue', 'ManagerController@getQueue');
Route::delete('admin/queue', 'ManagerController@deQueueByIndex');
Route::patch('admin/queue', 'ManagerController@deQueue');


if (env('APP_DEBUG', false)) {
    Route::get('test', function () {
        return view('index', [
            'title' => 'test',
            'type' => '__TEST__'
        ]);
    });

    Route::get('event', function () {
        Event::fire(
            new App\Events\broadcastSignerEvent(Signer::first(),
                request()->only(['department', 'tab'])));
        return 'Hello';
    });
}
