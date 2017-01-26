<?php

namespace App\Http\Controllers;

use App\Events\broadcastEndingInterviewEvent;
use App\Events\broadcastSignerEvent;
use App\Jobs\InformProscenium;
use Illuminate\Http\Request;

class ManagerController extends Auth\AuthController
{
    private static $STATUE = [
        '等待中' => 1,
        '就绪中' => 2,
        '面试中' => 3,
    ];

    private static $SET_QUEUE_KEY = [
        'signer' => 'signer',
        'interviewer-login' => 'interviewerLogin',
        'interviewer' => 'interviewer',
        'left-waiting-queue' => 'leftWaitingQueue'
    ];

    private static $MAP = [
        '移动组',
        'Web组',
        '美工组',
        '营销策划'
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
            'title' => '面试官登录'
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

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        $old = redis()->get($unique_id);
        $serialize_data = serialize($this->accept_data);
        $isPass = redis()->sadd(self::$SET_QUEUE_KEY['interviewer-login'], $serialize_data);

        if ($old && $isPass) {
            redis()->srem(self::$SET_QUEUE_KEY['interviewer-login'], $old);
        }

        $redirect = '/admin/department';

//        dd(redis()->get($unique_id));
        if ($isPass && redis()->set($unique_id, $serialize_data)) {
            return $this->ajax(response_array('登录成功！'));
        }

        return $this->ajax(
            response_array('已经登录过！', compact('redirect')),
            http_status('Unprocessable_Entity'));
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
            'student_id' => 'required|numeric',
            'name' => 'required',
            'department' => 'required|in:'.implode(',', self::$MAP)
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        $select = [ 'id', 'name', 'student_id',
            'major', 'grade', 'department', 'introduce' ];

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
        if (redis()->sadd(self::$SET_QUEUE_KEY['signer'], serialize($signer))) {
            $redis_key = redis()->rpush($signer['department'], serialize($signer));
        } else {
            return $this->ajax(
                response_array('已经成功签到！'),
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
        $this->accept_data = $request->only([ 'session_id' ]);

        $validator = $this->validator($this->accept_data, [
            'session_id' => 'required|alpha_num',
        ]);

        if ($validator->fails()) {
            return $this->ajax(
                response_array($validator->errors()->first()),
                http_status('Precondition_Failed'));
        }

        $group = unserialize(redis()->get($this->accept_data['session_id']));

        if ($group) {
            $cache_signer = unserialize(redis()->lindex($group['department'], 0));
            if (isset($cache_signer) &&
                $cache_signer['status'] === self::$STATUE['就绪中']) {
                $cache_signer['status'] = self::$STATUE['面试中'];
                $signer = redis()->lpop($group['department']);
                \Event::fire(new broadcastSignerEvent($group, $signer));
                return $this->getQueue();
            }

            $message = '确定'. $cache_signer['department']. '的' .$cache_signer['name'].' 已经处于就绪状态';
            return $this->ajax(response_array($message), http_status('Accepted'));
        }

        $errMsg = __CLASS__.'@'.__FUNCTION__.': '.$this->accept_data['session_id'].'不存在于redis数据库中或者redis数据库取值失败！';

        \Log::error($errMsg);
        return $this->ajax(response_array('error'), http_status('Bad_Request'));
    }

    /**
     * name : EndingInterview
     * description : 结束面试通知
     * @param Request $request
     * @return mixed
     */
    public function  EndingInterview(Request $request)
    {
        $unique_id = $request->session()->getId();
        $group = redis()->get($unique_id);

        if (! isset($group)) {
            return $this->ajax(
                response_array('您尚未登录或登录过期！'),
                http_status('Forbidden'));
        }

        $this->dispatch(new InformProscenium(
            unserialize($group), $this->_getQueue()));

        return $this->ajax(response_array('请等待...'));
    }

    /**
     * name : getQueue
     * description : 获取队列
     * @return mixed
     */
    public function getQueue()
    {
        return $this->ajax(response_array($this->_getQueue()));
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
        $signer = redis()->lindex(
            $this->accept_data['department'], $this->accept_data['index']);

        redis()->multi();

        // 获取要删除签到者的信息，便于在set集合中删除，预防再次签到时无法签到
        // 删除集合中的记录
        redis()->srem(self::$SET_QUEUE_KEY['signer'], $signer);

        /**根据索引删除**/
        redis()->lset($this->accept_data['department'],
            $this->accept_data['index'], 'delete');
        redis()->lrem($this->accept_data['department'],
            $this->accept_data['index'], 'delete');
        /****/

        redis()->exec();

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

    protected function getSet()
    {
        return array_map(function($value) {
            return unserialize($value);
        },redis()->smembers(self::$SET_QUEUE_KEY['signer']));
    }

    protected function _getQueue()
    {
        $redis_array = [];

        foreach (self::$MAP as &$value) {
            $length = redis()->llen($value);
            $redis = redis()->lrange($value, 0, $length);

            $redis = array_map(function ($value) {
                return unserialize($value);
            }, $redis);

//            krsort($redis); // 满足先进队列排最前

//            test(json$redis);
            $redis_array = array_merge($redis_array, [
                $value => array_values($redis)
            ]);
        }

        return $redis_array;
    }
}
