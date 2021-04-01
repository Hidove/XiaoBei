<?php

namespace Task;


class Xiaobei
{
    private $error = '';

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    public function login($username, $password)
    {
        $hidove_get = hidove_get('https://xiaobei.yinghuaonline.com/prod-api/captchaImage');
        $captcha = json_decode($hidove_get);
        if (empty($captcha) || $captcha->code !== 200) {
            $this->setError('小北学生认证系统发生异常，请联系管理员');
            return false;
        }
        $userInfo = [
            'username' => $username,
            'password' => $password,
            'code' => $captcha->showCode,
            'uuid' => $captcha->uuid
        ];
        $userInfo = json_encode($userInfo);
        $res = hidove_post('https://xiaobei.yinghuaonline.com/prod-api/login', $userInfo, '', [
            'Content-Type:application/json; charset=utf-8'
        ]);
        $res = json_decode($res);
        if (empty($res) || empty($res->code)) {
            $this->setError('登录返回数据异常，可能是小北学生服务器崩了，请联系管理员');
            return false;
        }
        if ($res->code != 200) {
            $this->setError($res->msg);
            return false;
        }
        return $res->token;
    }

    public function send($token, $postInfo)
    {
        $postInfo2 = [
            'temperature' => '36.6',
            'coordinates' => '中国-湖南省-长沙市-芙蓉区',
            'location' => '113.175338,28.171459',
            'healthState' => '1',
            'dangerousRegion' => '2',
            'dangerousRegionRemark' => '',
            'contactSituation' => '2',
            'goOut' => '1',
            'goOutRemark' => '',
            'familySituation' => '1',
            'remark' => ''
        ];
        foreach ($postInfo2 as $k => &$value) {
            if (isset($postInfo[$k])) {
                $value = $postInfo[$k];
            }
        }
        $postInfo = json_encode($postInfo2);
        $res = hidove_post('https://xiaobei.yinghuaonline.com/prod-api/student/health', $postInfo, '', [
            'Content-Type:application/json; charset=utf-8',
            'Authorization:Bearer ' . $token,
        ]);
        $res = json_decode($res);
        if (empty($res) || empty($res->code)) {
            $this->setError('体温上报返回数据异常，可能是小北学生服务器崩了，请联系管理员');
            return false;
        }
        if ($res->code != 200) {
            $this->setError($res->msg);
            return false;
        }
        return true;
    }
}