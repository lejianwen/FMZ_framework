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

    public function role()
    {
        $role = AdminRole::find($this->admin->role_id);
        return $this->jsonSuccess(['role' => $role, 'info' => $this->admin]);
    }

    protected function afterUpdate($item)
    {
        if ($item->password) {
            $item->password = md5($item->password);
            $item->save();
        }
    }
}
