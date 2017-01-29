<?php

namespace App\Http\Controllers;

use App\Events\broadcastMessageEvent;
use App\Events\broadcastSignerEvent;
use App\Jobs\InformProscenium;
use Illuminate\Http\Request;

class ManagerController extends Auth\AuthController
{
    public static $MAP = [
        '移动组',
        'Web组',
        '美工组',
        '营销策划'
    ];

    public static $STATUE = [
        '等待中' => 1,
        '就绪中' => 2,
        '面试中' => 3,
    ];

    public static $SET_QUEUE_KEY = [
        'signer' => 'signer',
        'interviewer-login' => 'interviewerLogin',
        'interviewer' => 'interviewer',
        'waiting-queue' => 'waitingQueue'
    ];
    /**
     * name : index
     * description : 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $response = [
            'title' => '后台管理 | 2017',
            '__ADMIN__' => true,
        ];

        return view('index', $response);
    }

    /**
     * name : show
     * description : 展示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $response = [
            'title' => '报名展示 | 2017',
            '__ADMIN__' => true,
        ];

        return view('index', $response);
    }

    /**
     * name : setDepartmentPage
     * description : 面试官登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setDepartmentPage()
    {
        return view('setDepartment', [
            'title' => '签到系统'
        ]);
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
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        // 过滤其他数据
        $this->accept_data = request()->only('account', 'password');

        // md5加密密码
        $this->accept_data['password'] = md5($this->accept_data['password']);

        $admin = \DB::table('manager')
            ->where('account', $this->accept_data['account'])
            ->first();

        $_password = isset($admin) ? $admin->password : null;

        // 密码不匹配时处理， 返回422
        if (strcmp($this->accept_data['password'], $_password)) {
            return $this->ajax(
                response_array('登录账号或密码出错！'),
                http_status('Unprocessable_Entity'));
        }

        $customClaims  = [
            'permission' => [
                json_decode('{"name": "all", "value": "全部", "isPermit": true}', true),
                json_decode('{"name": "android", "value": "移动组", "isPermit": true}', true),
                json_decode('{"name": "web", "value": "Web组", "isPermit": true}', true),
                json_decode('{"name": "design", "value": "美工组", "isPermit": true}', true),
                json_decode('{"name": "marking", "value": "营销策划", "isPermit": true}', true),
            ]
        ];

//        dd(json_decode('{"name": "marking", "value": "营销策划", "isPermit": true}'));
        // 登录成功获取token并返回
        $token = 'bearer ' . \JWTAuth::fromUser($admin, $customClaims);

        // 跳转链接
        $redirect = '/admin/show/all';

        return $this->ajax(
            response_array('登录成功！', compact('redirect', 'token')),
            http_status('OK'));
    }

    /**
     * name : permission
     * description : 权限管理
     * @return mixed
     */
    public function permission()
    {
        $permission = \JWTAuth::getPayload()->get('permission');

        $admin = false;

        if ($permission) {
            $admin = [
                'welcome' => '欢迎管理员，',
                'operate' => '退出',
                'skipTo' => [ 'name' => 'admin.login' ] ];
            return $this->ajax(
                response_array('success', compact('admin', 'permission')));
        }

        $redirect = '/admin';

        return $this->ajax(
            response_array('Permission_Not_Found', compact('admin', 'redirect')),
            http_status('Not_Found'));
    }

    /**
     * name : getSigners
     * description : 获取报名者列表
     * @return mixed
     */
    public function getSigners()
    {
        // 获取参数
        $pagination = [
            'page' => isset($this->accept_data['page']) ?
                $this->accept_data['page'] : 1,
            'size' => isset($this->accept_data['size']) ?
                $this->accept_data['size'] : 7,
        ];

        $accept_data = [];
        list($keys, $values) = array_divide(
            request()->only(['student_id', 'name', 'department']));
        array_map(function ($key, $value) use (&$accept_data) {
            if (!empty($value)) {
                $accept_data[$key] = $value;
            }
        }, $keys, $values);

        $select_params = [ 'id', 'student_id',
            'name', 'major', 'grade', 'phone_num', 'department' ];

        // 获取列表数据
        $info = \Signer::getSigner($accept_data, $select_params, $pagination);

        // 分离分页参数和数据
        $signers = array_pop($info);

//        dd($accept_data);
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

        $redirect = '/admin';

        //    验证失败处理，返回412
        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first(), compact('redirect')),
                http_status('Precondition_Failed'));
        }

        $select_params = [ 'id', 'student_id',
            'name', 'major', 'phone_num', 'department', 'introduce' ];

        $info = \Signer::getSigner($this->accept_data, $select_params);

        // 分离分页参数和数据
        $signer = array_pop($info);

        // 封装数据
        if (count($signer)) {
            $response = response_array('success', $signer[0]);
        }else {
            $response = response_array('No_Content');
        }

        // 判别是否有数据
        if ($response['count'] > 0) {
            return $this->ajax($response);
        }

        return $this->ajax(
            response_array(), http_status('No_Content'));
    }

    /***************************************************************************/

    /**
     * name : setDepartment
     * description : 面试官登录
     * @param Request $request
     * @return mixed
     */
    public function setDepartment(Request $request)
    {
        $this->accept_data = $request->only(['department', 'tab']);
        $unique_id = $request->session()->getId();

        $validator = $this->validator($this->accept_data, [
            'department' => 'required|in:'.implode(',', self::$MAP),
            'tab' => 'required|numeric'
        ]);

//        dd(config('queue.default'));
        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        $this->accept_data['tab'] = intval($this->accept_data['tab']);
        $serialize_data = serialize($this->accept_data);

        $old = redis()->get($unique_id);
        $isPass = redis()->sadd(self::$SET_QUEUE_KEY['interviewer-login'], $serialize_data);

        if ($old && $isPass) {
            redis()->srem(self::$SET_QUEUE_KEY['interviewer-login'], $old);
        }

        $redirect = '/admin/department/interview';

        if ($isPass && redis()->set($unique_id, $serialize_data)) {
            setcookie('department', $this->accept_data['department']);
            return $this->ajax(response_array('登录成功！', compact('redirect')));
        }

        return $this->ajax(
            response_array('已经登录过！', compact('redirect')),
            http_status('Unprocessable_Entity'));
    }

    /**
     * name : outDepartment
     * description : 面试官退出登录
     * @param Request $request
     * @return mixed
     */
    public function outDepartment(Request $request)
    {
        $unique_id = $request->session()->getId();

        $group = redis()->get($unique_id);

//        dd($group);
        redis()->multi();
        redis()->srem(self::$SET_QUEUE_KEY['interviewer-login'], $group);
        redis()->del([$unique_id]);
        redis()->exec();

        $redirect = '/admin/department';
        return $this->ajax(response_array('out success', compact('redirect')));
    }

    /**
     * name : enQueue
     * description : 入队签到
     * @param Request $request
     * @return mixed
     */
    public function enQueue(Request $request)
    {
        $signer_template = [
            "id" => 0,
            "student_id" => '',
            "name" => '',
            "major" => '',
            "phone_num" => '',
            "department" => '',
            "introduce" => '注意：没有线上报名',
            "status" => self::$STATUE['等待中']
        ];

        $this->accept_data = $request->only([ 'student_id', 'name', 'department' ]);

        $validator = $this->validator($this->accept_data, [
            'student_id' => 'required|digits:10',
            'name' => 'required',
            'department' => 'required|in:'.implode(',', self::$MAP)
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        $select = [ 'id', 'name', 'student_id',
            'major', 'grade', 'department', 'phone_num', 'introduce' ];

        $info = \Signer::getSigner($this->accept_data, $select);

        // 获取用户信息
        $signers = array_pop($info);

        // 判断是否线上报名
        if ($info['total'] > 0) {
            $signer = array_merge($signer_template, $signers[0]);
        } else {
            $signer = array_merge($signer_template, $this->accept_data);
        }

        $redis_key = 0;

//        dd($signer);
        // set集合防止重复签到
        if (redis('db')->sadd(self::$SET_QUEUE_KEY['signer'], serialize($signer))) {
            $redis_key = redis('db')->rpush($signer['department'], serialize($signer));
        } else {
            return $this->ajax(
                response_array('已经签到，请勿重复！'),
                http_status('Accepted'));
        }

        if ($redis_key > 0) {
            return $this->getQueue();
        }

        return $this->ajax(response_array('签到失败！'), http_status('Bad_Request'));
    }

    /**
     * name : deQueue
     * description : 出队
     * @param Request $request
     * @return mixed
     */
    public function deQueue(Request $request)
    {
        $this->accept_data = $request->only(['department']);

        $validator = $this->validator($this->accept_data, [
            'department' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Unprocessable_Entity'));
        }

        $cache_signer = unserialize(
            redis('db')->lindex($this->accept_data['department'], 0));

        if (isset($cache_signer) &&
            $cache_signer['status'] === self::$STATUE['就绪中']) {

            $cache_signer['status'] = self::$STATUE['面试中'];
            /**
             * 获取删除key的权限
             */
            $del_key = uniqid();

            redis()->multi();
            redis()->set($del_key, $del_key);
            redis()->expire($del_key, 30);
            redis()->exec();

            $cache_signer = array_add($cache_signer, 'del_key', $del_key);

            \Event::fire(
                new broadcastSignerEvent($this->accept_data, $cache_signer));

            return $this->getQueue();
        }

        $message = '请确认下一位面试者是否就绪！';
        return $this->ajax(response_array($message), http_status('Accepted'));
    }


    public function enSureDeQueueSuccess(Request $request)
    {
        $this->accept_data = $request->only([ 'del_key', 'department' ]);

        $this->accept_data['del_key_confirm'] = redis()->get($this->accept_data['del_key']);

        $validator = $this->validator($this->accept_data, [
            'del_key' => 'required|same:del_key_confirm',
            'department' => 'required|in:'.implode(',', self::$MAP)
        ]);

        if ($validator->fails()) {
            return $this->ajax(response_array(
                '你没有权限操作！'
            ), http_status('Forbidden'));
        }

        redis()->multi();
        redis()->del([ $this->accept_data['del_key'] ]);
        redis('db')->lpop($this->accept_data['department']);
        redis()->exec();

        \Event::fire(new broadcastMessageEvent('可以进去面试', $this->getQueueArray(), 'sign'));

        return $this->ajax(response_array());
    }

    /**
     * name : EndingInterview
     * description : 结束面试通知
     * @param Request $request
     * @return mixed
     */
    public function endingInterview(Request $request)
    {
        $unique_id = $request->session()->getId();
        $group = redis()->get($unique_id);
        $redirect = '/admin/department';

        if (! isset($group)) {
            return $this->ajax(
                response_array('您尚未登录或登录过期！', compact('redirect')),
                http_status('Forbidden'));
        }

//        $group = array_add(unserialize($group), 'session_id', $unique_id);
        $group = unserialize($group);
        $this->dispatch(new InformProscenium($group));

        return $this->ajax(response_array('请稍等...'));
    }

    /**
     * name : getQueue
     * description : 获取队列
     * @return mixed
     */
    public function getQueue()
    {
        return $this->ajax(response_array($this->getQueueArray()));
    }

    /**
     * name : delQueue
     * description :
     * @param Request $request
     * @return mixed
     */
    public function deQueueByIndex(Request $request)
    {
        $this->accept_data = $request->only([ 'department', 'index' ]);

        $validator = $this->validator($this->accept_data, [
            'department' => 'required|in:'.implode(',', self::$MAP),
            'index' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        /**
         * 注意list中索引是倒过来的，先进的元素索引最大，故需要把索引倒转
         */
//        $dept_list_length = redis()->llen($this->accept_data['department']);
//        $index = ($dept_list_length - 1) - $this->accept_data['index'];
        $signer = redis('db')->lindex(
            $this->accept_data['department'], $this->accept_data['index']);

        $unserialize_signer = unserialize($signer);

        if ($unserialize_signer['status'] !== self::$STATUE['等待中']) {
            return $this->ajax(
                response_array('该用户已经准备面试，不能删除！'),
                http_status('Forbidden'));
        }

        redis('db')->multi();

        // 获取要删除签到者的信息，便于在set集合中删除，预防再次签到时无法签到
        // 删除集合中的记录
        redis('db')->srem(self::$SET_QUEUE_KEY['signer'], $signer);

        /**根据索引删除**/
        redis('db')->lset($this->accept_data['department'],
            $this->accept_data['index'], 'delete');
        redis('db')->lrem($this->accept_data['department'],
            $this->accept_data['index'], 'delete');
        /****/

        redis('db')->exec();

        return $this->getQueue();
    }

    /**
     * name : testToken
     * description : 测试
     * @return mixed
     */
    public function testToken()
    {
        $payload = \JWTAuth::getPayload();
        dd($payload->get('permission'));
        return $this->ajax(response_array('ok', $payload->get('permission')));
    }

    /**
     * name : getQueueArray
     * description : 获取所有签到信息
     * @return array
     */
    public function getQueueArray()
    {
        $redis_array = [];

        foreach (self::$MAP as &$value) {
            $length = redis('db')->llen($value);
            $redis = redis('db')->lrange($value, 0, $length);

            $redis = array_map(function ($value) {
                return unserialize($value);
            }, $redis);

//            krsort($redis); // 满足先进队列排最前

//            test(json$redis);
            $redis_array = array_merge($redis_array, [
                self::mapDepartment($value) => array_values($redis)
            ]);
        }

        return $redis_array;
    }

    /**
     * name : mapDepartment
     * description : 获取对应的名字
     * @static      * @param $name
     * @return mixed
     */
    public static function mapDepartment($name)
    {
        static $map = [
            '移动组' => 'android',
            'Web组' => 'web',
            '美工组' => 'design',
            '营销策划' => 'marking',
        ];

        return $map[$name];
    }


    public function resetAll()
    {
        foreach (self::$MAP as &$value) {
            $data = redis('db')->lpop($value);

            $data = unserialize($data);

            if ($data) {

                $data['status'] = self::$STATUE['等待中'];

                redis('db')->lpush($value, serialize($data));
            }
        }

        return $this->getQueue();
    }
    /**
     * name : getSet
     * description :
     * @return array
     */
    protected function getSet()
    {
        return array_map(function($value) {
            return unserialize($value);
        },redis()->smembers(self::$SET_QUEUE_KEY['signer']));
    }
}
