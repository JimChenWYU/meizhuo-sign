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

/**
 * 首页显示路由
 */
Route::get('/', 'SignController@index');
Route::get('user', 'SignController@index');

/**
 * 报名路由
 */
Route::post('user', 'SignController@sign');

/**
 * 后台管理首页路由
 */
Route::get('admin', 'ManagerController@index');
Route::get('admin/login', 'ManagerController@index');

/**
 * 管理员登录路由
 */
Route::post('admin', 'ManagerController@login');

/**
 * 登录之后展示路由
 */
Route::get('admin/show', 'ManagerController@show');
Route::get('admin/show/{department}', 'ManagerController@show');
Route::get('admin/show/{department}/{id}', 'ManagerController@show');

/**
 * 登录之后可以获得Token, 凭借Token登录
 */
Route::group([ 'middleware' => [ 'jwt.auth' ] ], function () {

    /**
     * 获取所有的报名者
     */
    Route::get('admin/signers', 'ManagerController@getSigners');
    /**
     * 获取单个报名者信息
     */
    Route::get('admin/signer', 'ManagerController@getSigner');

    /**
     * 管理员权限获得
     */
    Route::get('admin/permission', 'ManagerController@permission');

    if (config('app.debug')) {
        Route::get('testToken', 'ManagerController@testToken');
    }
});

/*************************************以下是签到系统部门**************************************/

/**
 * 面试官首页登录确认组别
 */
Route::get('admin/department', 'ManagerController@setDepartmentPage');
Route::get('admin/department/sign', 'ManagerController@setDepartmentPage');
Route::get('admin/department/interview', 'ManagerController@setDepartmentPage');

/**面试官登录或退出**/
Route::post('admin/department', 'ManagerController@setDepartment');
Route::delete('admin/department', 'ManagerController@outDepartment');

/**结束面试通知**/
Route::put('admin/department', 'ManagerController@endingInterview');

/**签到系统签到入队**/
Route::get('admin/sign', 'ManagerController@enQueue');

/**获取签到队列**/
Route::get('admin/queue', 'ManagerController@getQueue');

/**根据索引删除**/
Route::delete('admin/queue', 'ManagerController@deQueueByIndex');

/**出队**/
Route::patch('admin/queue', 'ManagerController@deQueue');

/**确定面试官端收到信息**/
Route::put('admin/queue', 'ManagerController@enSureDeQueueSuccess');


if (config('app.debug')) {
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
