<?php
// 应用公共文件

function msg($code = 200, $msg = 'success', $data = [])
{
    return json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ]);
}

function hidove_get($url, $header = ['Content-Type: application/json'], $referer = null)
{
    // 创建一个新 cURL 资源
    $curl = curl_init();
    // 设置URL和相应的选项
    // 需要获取的 URL 地址
    curl_setopt($curl, CURLOPT_URL, $url);
    #启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_HEADER, false);
    #在尝试连接时等待的秒数。设置为 0，则无限等待。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    #允许 cURL 函数执行的最长秒数。
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    #关闭ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    #设置useragent
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36");   // 伪造ua
    #设置header
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    #伪造来源网址REFERER
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);        // 跟踪重定向
    curl_setopt($curl, CURLOPT_REFERER, empty($referer) ? $url : $referer);
    #取消gzip压缩
    //curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
    // 抓取 URL 并把它传递给浏览器
    $res = curl_exec($curl);
    // 关闭 cURL 资源，并且释放系统资源
    if ($res === false) {
        return "CURL Error:" . curl_error($curl);
    }
    curl_close($curl);
    return $res;
}


function hidove_post(
    $url,
    $post,
    $referer = 'https://www.baidu.com',
    $headers = ['User-Agent:Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50']
)
{

    // 创建一个新 cURL 资源
    $curl = curl_init();
    // 设置URL和相应的选项
    // 需要获取的 URL 地址
    curl_setopt($curl, CURLOPT_URL, $url);
    #启用时会将头文件的信息作为数据流输出。
    curl_setopt($curl, CURLOPT_HEADER, false);
    #设置头部信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    #在尝试连接时等待的秒数。设置为 0，则无限等待。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    #允许 cURL 函数执行的最长秒数。
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    #设置请求信息
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    #设置referer
    curl_setopt($curl, CURLOPT_REFERER, $referer);
    #关闭ssl
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // 抓取 URL 并把它传递给浏览器
    $return = curl_exec($curl);
    if ($return === false) {
        return "CURL Error:" . curl_error($curl);
    }
    curl_close($curl);
    return $return;
}

function get_user()
{
    $userId = \think\facade\Session::get('userId');
    if (empty($userId)) {
        return null;
    }
    $model = \app\model\User::where('id', $userId)->findOrEmpty();
    if ($model->isEmpty()) {
        return null;
    } else {
        return $model;
    }
}

function fomate_date($timestamp)
{
    return date('Y-m-d H:i:s', $timestamp);
}

function fomate_id($id)
{
    switch (strlen($id)) {
        case 11:
            $value = substr_replace($id, '******', 3, 6);
            break;
        default:
            $value = substr_replace($id, '*************', 3, 13);
    }
    return $value;
}