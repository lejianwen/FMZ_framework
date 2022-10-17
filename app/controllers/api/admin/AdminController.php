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

    protected function afterUpdate($item, $old = null)
    {
        if ($this->request->post('password')) {
            $item->password = md5($item->password);
            $item->token = '';
            $item->save();
        }
    }

    public function upPass($admin)
    {
        $up_admin = $this->model::find(request()->post('id'));
        if ($up_admin && $this->request->post('password')) {
            $up_admin->password = md5($this->request->post('password'));
            $up_admin->token = '';
            $up_admin->save();
            return $this->jsonSuccess();
        } else {
            return $this->jsonError();
        }
    }
}
