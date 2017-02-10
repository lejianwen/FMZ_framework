<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:11
 * QQ: 84855512
 */
namespace app\controllers;
use lib\session\session;

class IndexController extends BaseController
{
    public function index()
    {
        $this->view->with('framework', 'FMZ_framework')->display();
    }
}
