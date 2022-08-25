<?php


namespace app\controller;


use app\model\Log;
use Notification\Ftqq;
use Task\Xiaobei;
use think\facade\Cache;
use think\facade\Request;
use think\helper\Str;

class Api
{
    private $time;

    public function __construct()
    {
        $this->time = new \stdClass();
        $this->time->hour = date('H');
        $this->time->minute = date('i');
        $this->time->timestamp = strtotime(date('Y-m-d'));
        $this->time->now = time();
    }

    public function action()
    {
        $limit = Cache::get(__CLASS__ . '_' . __FUNCTION__ . '_limit', 10);
        $users = \app\model\User::whereTime('run_time', '<', $this->time->timestamp)
            ->where('status', 1)
            ->order('run_time', 'asc')
            ->limit($limit)
            ->select();
        $xiaobei = new Xiaobei();
        $message = [];
        $success = 0;
        $notifications = [];
        foreach ($users as $k => $v) {
            $cache_key = __CLASS__ . '_' . __FUNCTION__ . '_password_' . $v->username;
            if ($v->send_time->hour > $this->time->hour) {
                continue;
            }
            if ($v->send_time->hour == $this->time->hour && $v->send_time->minute > $this->time->minute) {
                continue;
            }
            $user_password = Cache::get($cache_key);
            if (!empty($user_password)) {
                if ($user_password === $v->password) {
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
                    $v->run_time = $this->time->now;
                    $result = '上报成功';
                    $success++;

                } else {
                    $v->run_time = $this->time->timestamp - 1;
                    Cache::set($cache_key, $v->password, 3600);
                    $result = $xiaobei->getError();
                }
            } else {
                $v->run_time = $this->time->timestamp - 1;
                Cache::set($cache_key, $v->password, 3600);
                $result = $xiaobei->getError();
            }
            // 停止执行账号异常、密码错误、已停用的账号
            if (Str::contains($result, ['用户不存在', '密码错误', '已停用'])) {
                $v->status = 0;
                $v->run_time = $this->time->now;
            }
            // 停止执行 目前时间未在标准时间之内 的账号
            if (Str::contains($result, ['目前时间未在标准时间之内'])) {
                if ($v->send_time->hour == $this->time->hour && $v->send_time->minute == $this->time->minute) {
                    $v->status = 0;
                    $v->run_time = $this->time->now;
                }
            }
            $result = str_replace($v->username, fomate_id($v->username), $result);
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
            if ($v->notification->status && !empty($v->notification->sct_key)) {
                $tmp = [
                    'title' => "小北助手 - 恭喜您今天打卡成功啦^_^",
                    'content' => "恭喜您今天打卡成功啦^_^  \n时间:" . date('Y-m-d H:i:s'),
                ];
                if ($result !== '上报成功') {
                    $tmp = [
                        'title' => "小北助手 - 打卡失败(╯︵╰)",
                        'content' => "打卡失败(╯︵╰)，{$result}  \n时间:" . date('Y-m-d H:i:s'),
                    ];
                }
                $tmp['sct_key'] = $v->notification->sct_key;
                $notifications[] = $tmp;
            }
        }
        if ($success < 1) {
            //防止淤积，逐步增长任务数目
            $limit++;
            Cache::set(__CLASS__ . '_' . __FUNCTION__ . '_limit', $limit);
        }
        // 通知
        foreach ($notifications as $v) {
            $notification = new Ftqq();
            !$notification->main($v) && \think\facade\Log::error($notification->getError());
        }
        return msg(200, '执行成功', $message);
    }

    public function status()
    {
        $logs = Log::order('create_time', 'desc')
            ->page(Request::param('page', 1))
            ->limit(Request::param('limit', 20))
            ->with(['user' => function ($query) {
                $query->field('id,username');
            }])
            ->select()->toArray();
        foreach ($logs as &$v) {

            $v['username'] = fomate_id($v['user']['username']);
            $v['message'] = str_replace($v['user']['username'], $v['username'], $v['message']);

            unset($v['user']);
        }

        return msg(200, 'success', $logs);

    }
}