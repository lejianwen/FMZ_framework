<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:11
 * QQ: 84855512
 */

namespace app\controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseController
{
    protected $request;

    public function index()
    {
        $this->response
            ->with(['framework' => 'FMZ_framework'])
            ->view('index/index');
    }

    public function test()
    {
        $this->request = \request();
        var_dump($this->request->getClientIp());
        var_dump($this->request->get());
    }

    public function middle()
    {
        echo 'middle';
    }
}
