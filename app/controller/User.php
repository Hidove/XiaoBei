<?php

namespace app\controller;

use app\BaseController;
use app\model\Log;
use Task\Xiaobei;
use think\facade\Cache;
use think\facade\Request;
use think\facade\View;

class User extends BaseController
{
    public function index()
    {
        $user = get_user();
        $hidove['user'] = $user;
        View::assign('hidove', $hidove);
        return View::fetch();
    }

    public function post()
    {
        $param = Request::only([
            'password',
            'coordinates',
            'location',
            'healthState',
            'dangerousRegion',
            'remark',
        ]);
        $user = get_user();
        $user->data($param)->save();
        return msg(200,'更新成功');

    }

    public function log()
    {
        $user = get_user();
        $logs = $user->logs()->limit(20)->order('create_time','desc')
            ->select();
        return msg(200, 'success', $logs);
    }

    public function test(){
        $user = get_user();
        $xiaobei = new Xiaobei();
        $token = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $user->username);
        if (empty($token)) {
            $token = $xiaobei->login($user->username, base64_encode($user->password));
            Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $user->username, $token, 3600);
        }
        if ($token) {
            $send = $xiaobei->send($token);
            if ($send) {
                $user->run_time = time();
                $message = '上报成功';
            } else {
                $message = $xiaobei->getError();
            }
        } else {
            $message = $xiaobei->getError();
        }
        Log::create([
            'user_id' => $user->id,
            'coordinates' => $user->coordinates,
            'location' => $user->location,
            'healthState' => $user->healthState,
            'dangerousRegion' => $user->dangerousRegion,
            'remark' => $user->remark,
            'message' => $message,
        ]);
        $user->create_time = strtotime($user->create_time);
        $user->save();
        if ($message === '上报成功'){
            return msg(200,$message);
        }
        return msg(400,$message);
    }
}
