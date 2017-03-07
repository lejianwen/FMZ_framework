<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/14
 * Time: 9:50
 * QQ: 84855512
 */
namespace lib;

class controller
{
    protected $view;
    protected $request;
    protected $session;
    protected $response;

    public function __construct()
    {
        $this->session = app('session');
        $this->request = app('request');
        $this->response = app('response');
    }

}