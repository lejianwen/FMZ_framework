<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/28
 * Time: 19:39
 */

namespace app\controllers\admin;

use app\models\Menu;

class MenuController extends BaseController
{
    protected $with = ['parent'];

    protected function grid()
    {
        parent::grid();
        $this->grid->setHeader(['ID', '父菜单ID', '菜单名', '路径', '图标', '排序', '状态', '创建时间', '更新时间']);
        $this->grid->text('id')->orderAble();
        $options = array_map(function ($menu) {
            return ['label' => $menu['name'], 'value' => $menu['id']];
        }, Menu::parentMenus());
        $options[] = ['label' => '根目录', 'value' => 0];
        $this->grid->select('pid')->options($options);
        $this->grid->text('name')->orderAble();
        $this->grid->text('path');
        $this->grid->icon('icon');
        $this->grid->text('sort')->orderAble();
        $this->grid->switchLabel('status')->options([
            ['label' => '禁用', 'value' => 0],
            ['label' => '启用', 'value' => 1, 'class' => 'label-success']
        ]);
        $this->grid->text('created_at');
        $this->grid->text('updated_at');
    }

    protected function form($item = null)
    {
        parent::form($item);
        $this->form->text('菜单名', 'name')->required();

        $options = array_map(function ($menu) {
            return ['label' => $menu['name'], 'value' => $menu['id']];
        }, Menu::parentMenus());
        $options[] = ['label' => '根目录', 'value' => 0, 'default' => 1];
        $this->form->select('父菜单', 'pid')->options($options);

        $this->form->text('路径', 'path');
        $this->form->text('图标', 'icon');
        $this->form->text('排序', 'sort')->default(0);
        $this->form->select('状态', 'status')->options([
            ['label' => '启用', 'value' => 1, 'default' => 1],
            ['label' => '禁用', 'value' => 0]
        ])->required();
    }
}