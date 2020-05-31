<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/9/30
 * Time: 21:34
 */

namespace app\controllers\api\admin;


use app\models\Admin;
use app\models\AdminRole;

class AdminController extends BaseController
{
    protected $filters = ['nickname' => 'like'];
    protected $with = ['role'];

    public function info($admin)
    {
        $role = AdminRole::find($admin->role_id);
        return $this->jsonSuccess(['role' => $role, 'info' => $admin]);
    }

    protected function afterUpdate($item, $is_create = false)
    {
        if ($this->request->post('password')) {
            $item->password = md5($item->password);
            $item->token = '';
            $item->save();
        }
    }

    public function upPass($admin)
    {
        if ($this->request->post('password')) {
            $admin->password = md5($this->request->post('password'));
            $admin->token = '';
            $admin->save();
            return $this->jsonSuccess();
        } else {
            return $this->jsonError();
        }
    }
}
