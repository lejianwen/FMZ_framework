<?php


namespace app\middleware;


use app\models\AdminLog;
use Ljw\Route\Middleware;

class Admin implements Middleware
{
    public function handle($params, $next)
    {
        $token = request()->header('admin-token');
        if (!$token) {
            response()->json(['code' => 403, 'msg' => '登陆超时']);
            return;
        }
        $admin = \app\models\Admin::where('token', $token)->first();
        if (!$admin || strtotime($admin->token_expire_time) < time()) {
            response()->json(['code' => 403, 'msg' => '登陆超时']);
            return;
        }
        $params[] = $admin;
        $next($params);
        // 添加操作日志
        try {
            AdminLog::create([
                'admin_id' => $admin->id,
                'ip' => request()->getClientIp(),
                'method' => request()->getMethod(),
                'uri' => request()->getPathInfo(),
                'query' => request()->getQueryString() ?: '',
                'post' => request()->post(),
                'remark' => '系统添加'
            ]);
        } catch (\Exception $e) {
            //可能query数据过长等，抓取异常
        }
    }
}
