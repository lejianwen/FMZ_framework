<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 10:27
 * QQ: 84855512
 */
namespace app\controllers;
use lib\view\smarty;
use common\request;
class BaseController
{
    protected $view;
    protected $request;

    public function __construct()
    {
        $this->initView();
    }

    /**初始化smarty模板
     *
     */
    protected function initView()
    {
        $this->request = request::_instance();
        $this->view = smarty::_instance();
        $this->view->setTpl(strtolower($this->request->getController()) . '/' . strtolower($this->request->getAction() . '.tpl'));
    }

}