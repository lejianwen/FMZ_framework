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
        $this->response
            ->with(['framework' => 'FMZ_framework'])
            ->view('index/index');
    }

    public function middle($middle_result)
    {
        echo $middle_result;
    }
}
