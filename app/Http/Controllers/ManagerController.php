<?php

namespace App\Http\Controllers;

use App\Http\Requests;

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

    public function show()
    {
        $response = [
            'title' => '报名展示 | 2017',
            '__ADMIN__' => true,
        ];

        return view('index', $response);
    }

    /**
     * name : login
     * description : 登录
     * @return mixed
     */
    public function login()
    {
        // 验证字段
        $validator = $this->validator($this->accept_data, [
            'account' => 'required',
            'password' => 'required'
        ]);

//        验证失败处理，返回412
        if ($validator->fails()) {
            return $this->ajax(
                $validator->errors()->first(),
                http_status('Precondition_Failed'));
        }

        // 过滤其他数据
        $this->accept_data = request()->only('account', 'password');

        // md5加密密码
        $this->accept_data['password'] = md5($this->accept_data['password']);

        $admin = \DB::table('manager')
            ->where('account',$this->accept_data['account'])
            ->first();

        $_password = isset($admin) ? $admin->password : null;

        // 密码不匹配时处理， 返回422
        if (strcmp($this->accept_data['password'], $_password)) {
            return $this->ajax(
                response_array('登录账号或密码出错！'),
                http_status('Unprocessable_Entity'));
        }

        // 登录成功获取token并返回
        $token = \JWTAuth::fromUser($admin);
        // 跳转链接
        $redirect = 'admin/show';

        return $this->ajax(
            response_array('登录成功！', compact('redirect', 'token')),
            http_status('OK'));
    }

    public function getSigners()
    {
        // 过滤其他数据
        $current_page = isset($this->accept_data['page']) ?
            $this->accept_data['page'] : 1;

        $accept_data = [];
        list($keys, $values) = array_divide(
            request()->only('student_id', 'name', 'department'));
        array_map(function ($key, $value) use(&$accept_data) {
            if (isset($value)) {
                $accept_data[$key] = $value;
            }
        }, $keys, $values);

        $select_params = [ 'id', 'student_id',
            'name', 'major', 'phone_num', 'department' ];

        // 获取列表数据
        $info = \Signer::getSigner($accept_data, $current_page, $select_params);

        // 分离分页参数和数据
        $signers = array_pop($info);

        // 封装数据
        $response = response_array('success', $signers);

        // 判别是否有数据
        if ($response['count'] > 0) {
            return $this->ajax(
                array_merge($info, $response));
        }

        return $this->ajax(
            response_array(), http_status('No_Content'));
    }

    /**
     * name : getSigner
     * description : 获取指定id用户信息
     * @return mixed
     */
    public function getSigner()
    {
        $this->accept_data = request()->only('id');

        $validator = $this->validator($this->accept_data, [
            'id' => 'required|numeric'
        ]);

        //        验证失败处理，返回412
        if ($validator->fails()) {
            return $this->ajax(
                $validator->errors()->first(),
                http_status('Precondition_Failed'));
        }

        $select_params = [ 'id', 'student_id',
            'name', 'major', 'phone_num', 'department', 'introduce' ];

        $info = \Signer::getSigner($this->accept_data, $select_params);

        // 分离分页参数和数据
        $signer = array_pop($info);

        // 封装数据
        $response = response_array('success', $signer[0]);

        // 判别是否有数据
        if ($response['count'] > 0) {
            return $this->ajax($response);
        }

        return $this->ajax(
            response_array(), http_status('No_Content'));
    }

    public function testToken()
    {
        return $this->ajax(response_array('ok', \JWTAuth::getToken()));
    }
}
