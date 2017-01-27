<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class SignController extends Auth\AuthController
{
    public function index()
    {
        $response = [
            'title' => 'MeiZhuoStdio | 2017',
            '__USER__' => true,
        ];

        return view('index', $response);
    }
    /**
     * function : sign
     * description :
     * @return mixed
     */
    public function sign()
    {
        if (env('IS_END', false)) {
            return $this->ajax(
                response_array('已经截止报名，请勿尝试！'),
                http_status('Forbidden'));
        }

        $validator = $this->validator($this->accept_data, [
            'name' => 'required',
            'student_id' => 'required|digits:10',
            'major' => 'required',
            'phone_num' => 'required',
            'grade' => 'required',
            'department' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                $validator->errors()->first(),
                http_status('Precondition_Failed'));
        }

        $response = \Signer::insertSigner($this->accept_data);

        if (isset($response['state']) && !$response['state']) {
            return $this->ajax(
                response_array($response['msg']),
                http_status('Insufficient_Storage'));
        }

        return $this->ajax(response_array($response['msg']), http_status('OK'));
    }
}
