<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/22
 * Time: 22:33
 */

namespace app\controllers\admin;

use app\controllers\admin\html\Form;

class UserController extends BaseController
{
    protected $search_columns = ['id', 'nick_name'];

    protected function grid()
    {
        parent::grid();
        $this->grid->setHeader(['id', '昵称', '头像', '金币', '创建时间']);
        $this->grid->text('id');
        $this->grid->text('nick_name');
        $this->grid->img('headimg');
        $this->grid->text('gold');
        $this->grid->text('created_at');
    }

    protected function form($item = null)
    {
        $form = new Form($item);
        $form->text('昵称', 'nick_name')->required();
        return $form;
    }
}