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
use app\controllers\admin\html\Grid;

class UserController extends BaseController
{
    protected $search_columns = ['id', 'nick_name'];

    protected function grid()
    {
        $grid = new Grid();
        $grid->setHeader(['id', '昵称', '头像', '金币', '创建时间']);
        $grid->text('id');
        $grid->text('nick_name');
        $grid->img('headimg');
        $grid->text('gold');
        $grid->text('created_at');
        return $grid;
    }

    protected function form($item = null)
    {
        $form = new Form($item);
        $form->text('昵称', 'nick_name')->required();
        return $form;
    }
}