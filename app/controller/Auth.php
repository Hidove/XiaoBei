<?php


namespace app\controller;


use app\model\User;
use Task\Xiaobei;
use think\facade\Request;
use think\facade\Session;


class Auth
{

    public function login()
    {
        $driver = new Xiaobei();
        $username = Request::param('username');
        $password = Request::param('password');

        $res = $driver->login($username, base64_encode($password));
        if ($res) {
            $model = User::where('username', $username)->findOrEmpty();
            if ($model->isEmpty()) {
                $model = User::create([
                    'username' => $username,
                    'password' => $password,
                    'coordinates' => '中国-湖南省-长沙市-芙蓉区',
                    'location' => '113.175338,28.171459',
                    'healthState' => '1',
                    'dangerousRegion' => '2',
                    'dangerousRegionRemark' => '',
                    'contactSituation' => '2',
                    'goOut' => '1',
                    'goOutRemark' => '',
                    'familySituation' => '1',
                    'remark' => '',
                    'status' => 1,
                    'send_time' => ['hour' => 0, 'minute' => 0],
                    'run_time' => 0,
                ]);
            } else {
                if ($model->password !== $password) {
                    $model->password = $password;
                    $model->save();
                }
            }
            Session::set('userId', $model->id);
            return msg(200, '登录成功');
        }
        return msg(400, $driver->getError());
    }

    public function logout()
    {
        Session::clear();
        return redirect('/');
    }
}