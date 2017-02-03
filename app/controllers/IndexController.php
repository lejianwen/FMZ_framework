<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:11
 * QQ: 84855512
 */
namespace app\controllers;
class IndexController extends BaseController
{
    public function index()
    {
        echo 'simple laravel is ok!';
        $this->view->display();
    }
}
