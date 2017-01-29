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
Route::get('admin/department/sign', 'ManagerController@setDepartmentPage');
Route::get('admin/department/interview', 'ManagerController@setDepartmentPage');

/**面试官登录或退出**/
Route::post('admin/department', 'ManagerController@setDepartment');
Route::delete('admin/department', 'ManagerController@outDepartment');

/**结束面试通知**/
Route::put('admin/department', 'ManagerController@endingInterview');

Route::get('admin/sign', 'ManagerController@enQueue');
Route::get('admin/queue', 'ManagerController@getQueue');
Route::delete('admin/queue', 'ManagerController@deQueueByIndex');
Route::patch('admin/queue', 'ManagerController@deQueue');
Route::put('admin/queue', 'ManagerController@enSureDeQueueSuccess');


if (env('APP_DEBUG', false)) {
    Route::get('test', function (\Illuminate\Http\Request $request) {
        setcookie('session_id', $request->session()->getId());
        return $request->session()->getId();
    });

    Route::get('event', function () {
//        dd(Signer::first()->toArray());
        Event::fire(
            new App\Events\broadcastSignerEvent(request()->only(['department', 'tab']), Signer::first()->toArray()));
        return 'Hello';
    });

    Route::get('redis', function () {
        Redis::publish('channel-update-success', json_encode(['foo' => 'bar']));
    });

    Route::get('reset', 'ManagerController@resetAll');
}
