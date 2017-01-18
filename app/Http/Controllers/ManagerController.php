<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;

class ManagerController extends Auth\AuthController
{
    public function index()
    {
        $response = [
            'title' => '后台管理 | 2017',
            '__ADMIN__' => true,
        ];

        return view('index', $response);
    }


    public function login()
    {
        $validator = $this->validator($this->accept_data, [
            'account' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                $validator->errors()->first(),
                http_status('Precondition_Failed'));
        }

        // 过滤其他数据
        $this->accept_data = request()->only('account', 'password');

        $this->accept_data['password'] = md5($this->accept_data['password']);

        $admin = \DB::table('manager')
            ->where('account',$this->accept_data['account'])
            ->first();

        $_password = isset($admin) ? $admin->password : null;

        if (strcmp($this->accept_data['password'], $_password)) {
            return $this->ajax(
                response_array('登录账号或密码出错！'),
                http_status('Unprocessable_Entity'));
        }

        $token = \JWTAuth::fromUser($admin);
        $redirect = 'admin/show';

        return $this->ajax(
            response_array('登录成功！', compact('redirect', 'token')),
            http_status('OK'));
    }

    public function testToken()
    {
        return $this->ajax(response_array('ok'));
    }
}
