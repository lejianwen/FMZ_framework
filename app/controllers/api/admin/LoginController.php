<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/20
 * Time: 17:23
 * QQ: 84855512
 */

namespace app\controllers\api\admin;

use app\models\Admin;
use lib\controller;

class LoginController extends controller
{
    public function login()
    {
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $admin = Admin::where(['username' => $username, 'password' => md5($password)])->first();

        if (!$admin || !$admin->id) {
            return $this->response->json(['code' => 401, 'msg' => '用户名或密码错误!']);
        } else {
            $admin->token = md5($admin->id . mt_rand(1000,9999));
            $admin->token_expire_time = date('Y-m-d H:i:s', strtotime("+ 30 days"));
            $admin->save();
            return $this->response->json(['code' => 200, 'msg' => '登录成功!', 'token' => $admin->token, 'info' => $admin]);
        }

    }

    public function logout()
    {
        return $this->response->json(['code' => 200, 'msg' => '登出成功!']);
    }
}
