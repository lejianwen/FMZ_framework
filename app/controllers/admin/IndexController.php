<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/20
 * Time: 17:23
 * QQ: 84855512
 */

namespace app\controllers\admin;

use app\models\Menu;

class IndexController extends BaseController
{
    public function index()
    {
        $session = app('session');
        $user_name = $session->get('admin_username');
        $group_id = $session->get('admin_group_id');
        $menus = Menu::allMenus();
        $this->response
            ->with([
                'admin'          => $user_name,
                'admin_group_id' => $group_id,
                'menus'          => $menus

            ])
            ->view('admin/index');
    }

    public function welcome()
    {
        $this->response->view('admin/welcome');
    }
}