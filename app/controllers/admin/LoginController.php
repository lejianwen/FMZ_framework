<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/20
 * Time: 17:23
 * QQ: 84855512
 */
namespace app\controllers\admin;

use app\models\Admin;
use lib\controller;

class LoginController extends controller
{
    public function index()
    {
        $this->response
            ->view('admin/login');
    }

    public function login_post()
    {
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $admin = Admin::where(['username' => $username, 'password' => md5($password)])->first();
        if (!$admin || !$admin->id) {
            return $this->response->json(['error' => 101, 'msg' => '用户名或密码错误!']);
        } else {
            app('session')->set('admin_id', $admin->id);
            app('session')->set('admin_username', $admin->username);
            app('session')->set('admin_nickname', $admin->nick_name);
            app('session')->set('admin_group_id', $admin->group_id);
            app('session')->set('admin_expire', strtotime("+1 hour"));
            return $this->response->json(['error' => 0, 'msg' => '登录成功!']);
        }

    }

    public function logout()
    {
        app('session')->del('admin_id');
        app('session')->del('admin_username');
        app('session')->del('admin_nickname');
        app('session')->del('admin_expire');
        app('session')->del('admin_group_id');
        $this->response->redirect('/admin/login/index');
    }
}