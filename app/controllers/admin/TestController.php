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
        $form->time('时间', 'created_at');
        $form->time('时间', 'updated_at')->format('yyyy-MM-dd');
        $form->select('状态', 'status')->options([
            ['label' => '启用', 'value' => 1],
            ['label' => '禁用', 'value' => 0, 'default' => 1]
        ])->required();
        return $form;
    }

    protected function grid()
    {
        parent::grid();
        $this->grid->setHeader(['ID', '名称', '图片', '管理员', '状态']);
        $this->grid->text('id')->orderAble();
        $this->grid->label('name')->orderAble();
        $this->grid->img('images');
        $this->grid->text('admins.username');
        $this->grid->switchLabel('status', [
            ['label' => '禁用', 'value' => 0, 'class' => 'label-grey'],
            ['label' => '启用', 'value' => 1, 'class' => 'label-success']
        ]);
        $this->grid->action()->append('<button class="btn update" value="\'+value+\'">测试</button>');

//        $grid->disAbleAdd();

    }

    protected function search()
    {
        parent::search();
        $this->search_form->eq('NAME', 'name');
        $this->search_form->eq('STATUS', 'status', 'select')->options([
            ['label' => '==请选择==', 'value' => null],
            ['label' => '禁用', 'value' => 0],
            ['label' => '启用', 'value' => 1, 'default' => 1]
        ]);
//        $this->search_form->equal(function ($search_form) {
//            return $search_form->select('STATUS', 'status')->options([
//                ['label' => '禁用', 'value' => 0, 'class' => 'label-grey'],
//                ['label' => '启用', 'value' => 1, 'class' => 'label-success']
//            ]);
//        });
    }
}