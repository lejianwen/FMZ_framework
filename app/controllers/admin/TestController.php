<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/11
 * Time: 9:55
 */

namespace app\controllers\admin;

use app\controllers\admin\html\form\Embeds;

class TestController extends BaseController
{
    protected $search_columns = ['id', 'name'];
    protected $with = ['admins'];

    protected function form($item = null)
    {
        parent::form($item);
        $this->form->text('名称', 'name')->required();
//        $this->form->file('附件', 'file')->isMultiple();
        $this->form->image('图片', 'images')->accept('.png,.jpg');
        $this->form->embeds('位置', 'positions', function (Embeds $form) {
            $form->text('位置', 'position');
            $form->text('位置2', 'position2');
            $form->embeds('子位置', 'pos', function (Embeds $form) {
                $form->text('位置3', 'position3');
                $form->text('位置4', 'position4');
            });
        });
        $this->form->time('时间', 'created_at');
        $this->form->time('时间', 'updated_at')->format('yyyy-MM-dd');
        $this->form->select('状态', 'status')->options([
            ['label' => '启用', 'value' => 1],
            ['label' => '禁用', 'value' => 0, 'default' => 1]
        ])->required();

    }

    protected function grid()
    {
        parent::grid();
        $this->grid->setHeader(['ID', '名称', '图片', '管理员', '创建时间', '更新时间', '状态']);
        $this->grid->text('id')->orderAble();
        $this->grid->label('name')->orderAble();
        $this->grid->img('images');
        $this->grid->text('admins.username');
        $this->grid->text('created_at');
        $this->grid->text('updated_at');
        $this->grid->switchLabel('status', [
            ['label' => '禁用', 'value' => 0, 'class' => 'label-grey'],
            ['label' => '启用', 'value' => 1, 'class' => 'label-success']
        ]);
        $this->grid->text('book_path')->display(function ($value, $item) {
            return "<a href='https://m.ibookv.com/mipBook/{$item['id']}.html' target='_blank'>{$value}</a><br>";
        });
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
            ['label' => '启用', 'value' => 1]
        ]);
        $this->search_form->egt('创建时间', 'created_at', 'time');
//        $this->search_form->eq(function ($search_form) {
//            return $search_form->select('STATUS', 'status')->options([
//                ['label' => '禁用', 'value' => 0, 'class' => 'label-grey'],
//                ['label' => '启用', 'value' => 1, 'class' => 'label-success']
//            ]);
//        });
    }
}