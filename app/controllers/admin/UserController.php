<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/22
 * Time: 22:33
 */

namespace app\controllers\admin;

use app\controllers\admin\html\Grid;
use app\controllers\admin\html\Form;

class UserController extends BaseController
{
    protected $search_columns = ['id', 'nick_name'];

    protected function grid()
    {
        $grid = new Grid();
        $grid->setHeader(['ID', '昵称', '状态']);
        $grid->text('id')->orderAble();
        $grid->text('nick_name')->orderAble();
        $grid->select('status', [['label' => '禁用', 'value' => 0], ['label' => '启用', 'value' => 1]]);
        $grid->action()->append('<button class="btn update" value="\'+value+\'">测试</button>');
//        $grid->disAbleAdd();
        return $grid;
    }

    protected function form($item = null)
    {
        $form = new Form($item);
        $form->text('题目', 'content')->required();
        $form->select('状态', 'status', [
            ['label' => '启用', 'value' => 1, 'default' => 1],
            ['label' => '禁用', 'value' => 0]
        ])->required();
        return $form;
    }
}