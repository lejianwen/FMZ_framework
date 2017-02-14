<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/14
 * Time: 9:50
 * QQ: 84855512
 */
namespace lib;

use lib\http\request;
use lib\http\response;

class controller
{
    protected $view;
    protected $request;
    protected $session;
    protected $response;

    public function __construct()
    {
        $this->session = new session();
        $this->request = request::_instance();
        $this->response = new response();
        $this->view = view::_instance();
        $this->initView();
    }

    /**初始化视图模板
     *
     */
    protected function initView()
    {

        $this->view->setTpl(strtolower($this->request->getController()) . '/' . strtolower($this->request->getAction() . '.tpl'));
    }

    protected function response()
    {

    }
}