<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/11
 * Time: 9:55
 */

namespace app\controllers\admin;

use app\controllers\admin\html\Form;
use app\controllers\admin\html\Grid;

class TestController extends BaseController
{
    protected $search_columns = ['id', 'name'];
    protected $with = ['admins'];

    protected function form($item = null)
    {
        $form = new Form($item);
        $form->text('名称', 'name')->required();
//        $form->file('附件', 'file')->isMultiple();
        $form->image('图片', 'images')->accept('.png,.jpg');
        $form->select('状态', 'status')->options([
            ['label' => '启用', 'value' => 1, 'default' => 1],
            ['label' => '禁用', 'value' => 0]
        ])->required();
        return $form;
    }

    protected function grid()
    {
        $grid = new Grid();
        $grid->setHeader(['ID', '名称', '图片', '管理员', '状态']);
        $grid->text('id')->orderAble();
        $grid->text('name')->orderAble();
        $grid->img('images');
        $grid->text('admins.username');
        $grid->select('status', [['label' => '禁用', 'value' => 0], ['label' => '启用', 'value' => 1]]);
        $grid->action()->append('<button class="btn update" value="\'+value+\'">测试</button>');

//        $grid->disAbleAdd();
        return $grid;
    }
}