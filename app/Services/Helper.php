<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/01/17
 * Time: 下午 04:24
 */

if (! function_exists('test')) {
    function test($msg = 'test', $exit = true)
    {
        echo '<pre>';
        var_dump($msg);
        echo '</pre>';

        if ($exit) {
            exit(0);
        }
    }
}

if (! function_exists('response_array')) {
    function response_array($msg='please input message', $data=[])
    {
        if (is_array($msg) || is_object($msg)) {
            $data = $msg;
            $msg = 'please input message';
        }

        $count = is_array($data) ? count($data) : (is_object($data) ? 1 : 0);

        return [
            'msg' => $msg,
            'count' => $count,
            'data' => $data,
        ];
    }
}

if (! function_exists('http_status')) {
    function http_status($status)
    {
        static $_status = [
            'OK' => 200,
            'Accepted' => 202,
            'No_Content' => 204,

            'Bad_Request'=> 400,
            'Unauthorized' => 401,
            'Forbidden' => 403,
            'Not_Found' => 404,
            'Method_Not_Allowed' => 405,

            // 请求被服务器正确解析，但是包含无效字段
            'Unprocessable_Entity' => 422,
            // 服务器在验证在请求的头字段中给出先决条件时，没能满足其中的一个或多个。
            'Precondition_Failed' => 412,
            // 服务器无法存储完成请求所必须的内容。这个状况被认为是临时的。
            'Insufficient_Storage' => 507,
        ];

        return $_status[$status];
    }
}

if (! function_exists('redis')) {
    function redis()
    {
        return Redis::connection();
    }
}

if (! function_exists('define_constant ')) {
    function define_constant($name, $value='')
    {
        return empty($value) ? defined($name) :
            (defined($name) ? false : define($name, $value));
    }
}