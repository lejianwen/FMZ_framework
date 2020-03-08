<?php


namespace app\middleware;


use Ljw\Route\Middleware;

class Admin implements Middleware
{
    public function handle($params, $next)
    {
        $token = request()->header('admin-token');
        if (!$token) {
            return;
        }
        $admin = \app\models\Admin::where('token', $token)->first();
        if (!$admin || strtotime($admin->token_expire_time) < time()) {
            response()->json(['code' => 403, 'msg' => '登陆超时']);
            return;
        }
        $params[] = $admin;
        $next($params);
    }
}
