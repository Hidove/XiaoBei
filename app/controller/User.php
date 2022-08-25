<?php

namespace app\controller;

use app\BaseController;
use app\model\Log;
use Notification\Ftqq;
use Task\Xiaobei;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Validate;
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
            'temperature',
            'coordinates',
            'location',
            'healthState',
            'dangerousRegion',
            'dangerousRegionRemark',
            'contactSituation',
            'goOut',
            'goOutRemark',
            'familySituation',
            'remark',
            'status',
            'send_time',
            'notification',
        ]);
        $validate = Validate::rule([
            'password' => 'require',
            'temperature' => 'require',
            'coordinates' => 'require',
            'location' => 'require',
            'healthState' => 'require',
            'dangerousRegion' => 'require',
//            'dangerousRegionRemark' => 'require',
            'contactSituation' => 'require',
            'goOut' => 'require',
//            'goOutRemark' => 'require',
            'familySituation' => 'require',
//            'remark' => 'require',
            'status' => 'require',
            'send_time' => 'require',
            'notification' => 'require',
        ]);
        if (!$validate->check($param)) {
            return msg(400, $validate->getError());
        }
        if (strpos($param['location'], ',') === -1) {
            return msg(400, '地理坐标格式有误，需以英文逗号分割');
        }
        $param['notification']['status'] = intval($param['notification']['status']);
        if ($param['notification']['status'] == 1 && $param['notification']['sct_key'] == "") {
            return msg(400, 'Server酱SendKey不得为空');
        }
        $user = get_user();
        $user->data($param)->save();
        return msg(200, '更新成功');

    }

    public function log()
    {
        $user = get_user();
        $logs = $user->logs()->limit(20)->order('create_time', 'desc')
            ->select();
        return msg(200, 'success', $logs);
    }

    public function test()
    {
        $user = get_user();
        $xiaobei = new Xiaobei();
        $token = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $user->username);
        if (empty($token)) {
            $token = $xiaobei->login($user->username, base64_encode($user->password));
            Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $user->username, $token, 3600);
        }
        if ($token) {
            $send = $xiaobei->send($token, $user);
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
            'temperature' => $user->temperature,
            'coordinates' => $user->coordinates,
            'location' => $user->location,
            'healthState' => $user->healthState,
            'dangerousRegion' => $user->dangerousRegion,
            'dangerousRegionRemark' => $user->dangerousRegionRemark,
            'contactSituation' => $user->contactSituation,
            'goOut' => $user->goOut,
            'goOutRemark' => $user->goOutRemark,
            'familySituation' => $user->familySituation,
            'remark' => $user->remark,
            'message' => $message,
        ]);
        $user->save();
        $tmp = [
            'title' => "小北助手 - 恭喜您今天打卡成功啦^_^",
            'content' => "恭喜您今天打卡成功啦^_^  \n时间:" . date('Y-m-d H:i:s'),
        ];
        if ($message !== '上报成功') {
            $tmp = [
                'title' => "小北助手 - 打卡失败(╯︵╰)",
                'content' => "打卡失败(╯︵╰)，{$message}  \n时间:" . date('Y-m-d H:i:s'),
            ];
        }
        if ($user->notification->status && !empty($user->notification->sct_key)) {
            $tmp['sct_key'] = $user->notification->sct_key;
            $notification = new Ftqq();
            !$notification->main($tmp) && \think\facade\Log::error($notification->getError());
        }
        if ($message === '上报成功') {
            return msg(200, $message);
        }
        return msg(400, $message);
    }
}
