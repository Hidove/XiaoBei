<?php
declare (strict_types = 1);

namespace app\middleware;

class UserAuth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return \think\response\Redirect
     */
    public function handle($request, \Closure $next)
    {
        $user = get_user();
        if (empty($user)){
            return redirect('/');
        }
        return $next($request);
    }
}
