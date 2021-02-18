<?php
declare (strict_types = 1);

namespace app\middleware;

class AdminAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return \think\response\Json
     */
    public function handle($request, \Closure $next)
    {
        $token = $request->param('token');
        if ($token !== env('config.token')){
            return msg(400,'token错误');
        }
        return $next($request);
    }
}
