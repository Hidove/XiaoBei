<?php


namespace app\controller;


use app\model\Log;
use Task\Xiaobei;
use think\facade\Cache;
use think\facade\Request;

class Api
{

    public function action()
    {
        $limit = Request::param('limit', 10);

        $Users = \app\model\User::whereTime('run_time', '<', strtotime(date('Y-m-d')))
            ->where('status',1)
            ->order('run_time', 'asc')
            ->limit($limit)
            ->select();
        $xiaobei = new Xiaobei();
        $message = [];
        foreach ($Users as $k => $v) {
            $token = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $v->username);
            if (empty($token)) {
                $token = $xiaobei->login($v->username, base64_encode($v->password));
                Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_token_' . $v->username, $token, 3600);
            }
            if ($token) {
                $send = $xiaobei->send($token);
                if ($send) {
                    $v->run_time = time();
                    $result = '上报成功';
                } else {
                    $result = $xiaobei->getError();
                }
            } else {
                $result = $xiaobei->getError();
            }
            $message[$k] = fomate_id($v->username) . '：' . $result;
            Log::create([
                'user_id' => $v->id,
                'coordinates' => $v->coordinates,
                'location' => $v->location,
                'healthState' => $v->healthState,
                'dangerousRegion' => $v->dangerousRegion,
                'remark' => $v->remark,
                'message' => $result,
            ]);
            $v->create_time = strtotime($v->create_time);
            $v->save();
        }
        return msg(200, '执行成功', $message);
    }

    public function status()
    {
        $logs = Log::order('create_time', 'desc')
            ->limit(20)
            ->with(['user' => function ($query) {
                $query->field('id,username');
            }])
            ->select()->toArray();
        foreach ($logs as &$v) {

            $v['username'] = fomate_id($v['user']['username']);
            unset($v['user']);
        }

        return msg(200, 'success', $logs);

    }
}