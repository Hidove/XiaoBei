<?php


namespace app\controller;


use app\model\Log;
use Task\Xiaobei;
use think\facade\Cache;
use think\facade\Request;
use think\helper\Str;

class Api
{

    public function action()
    {
        $limit = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_limit', 10);
        $Users = \app\model\User::whereTime('run_time', '<', strtotime(date('Y-m-d')))
            ->where('status', 1)
            ->order('run_time', 'asc')
            ->limit($limit)
            ->select();
        $xiaobei = new Xiaobei();
        $message = [];
        $success = 0;
        $result = '';
        foreach ($Users as $k => $v) {
            if ($v->send_time->hour > date('H')) {
                continue;
            }
            if ($v->send_time->hour == date('H') && $v->send_time->minute >= date('i')) {
                continue;
            }
            $userPassword = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_password_' . $v->username);
            if (!empty($userPassword)) {
                if ($userPassword === $v->password) {
                    continue;
                }

            }

            $token = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $v->username);
            if (empty($token)) {
                $token = $xiaobei->login($v->username, base64_encode($v->password));
                Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $v->username, $token, 3600);
            }
            if ($token) {
                $send = $xiaobei->send($token, $v);
                if ($send) {
                    $v->run_time = time();
                    $result = '上报成功';
                    $success++;

                } else {
                    $v->run_time = strtotime(date('Y-m-d')) - 1;

                    Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_password_' . $v->username, $v->password,3600);
                    $result = $xiaobei->getError();
                }
            } else {
                $v->run_time = strtotime(date('Y-m-d')) - 1;
                Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_password_' . $v->username, $v->password,3600);
                $result = $xiaobei->getError();
                // 停止执行账号异常、密码错误、已停用的账号
                if(Str::contains($result,['用户不存在','密码错误','已停用'])){
                    $v->status = 0;
                }
            }
            $result = str_replace($v->username,fomate_id($v->username),$result);
            $message[$k] = fomate_id($v->username) . '：' . $result;
            Log::create([
                'user_id' => $v->id,
                'temperature' => $v->temperature,
                'coordinates' => $v->coordinates,
                'location' => $v->location,
                'healthState' => $v->healthState,
                'dangerousRegion' => $v->dangerousRegion,
                'dangerousRegionRemark' => $v->dangerousRegionRemark,
                'contactSituation' => $v->contactSituation,
                'goOut' => $v->goOut,
                'goOutRemark' => $v->goOutRemark,
                'familySituation' => $v->familySituation,
                'remark' => $v->remark,
                'message' => $result,
            ]);
            $v->save();
        }
        if ($success < 1) {
            //防止淤积，逐步增长任务数目
            $limit++;
            Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_limit', $limit);
        }
        return msg(200, '执行成功', $message);
    }

    public function status()
    {
        $logs = Log::order('create_time', 'desc')
            ->page(Request::param('page',1))
            ->limit(Request::param('limit',20))
            ->with(['user' => function ($query) {
                $query->field('id,username');
            }])
            ->select()->toArray();
        foreach ($logs as &$v) {

            $v['username'] = fomate_id($v['user']['username']);
            $v['message'] = str_replace($v['user']['username'],$v['username'],$v['message']);

            unset($v['user']);
        }

        return msg(200, 'success', $logs);

    }
}