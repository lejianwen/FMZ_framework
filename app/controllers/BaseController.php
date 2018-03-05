<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 10:27
 * QQ: 84855512
 */

namespace app\controllers;

use lib\controller;

class BaseController extends controller
{

    public function jsonError($msg = '操作失败', $code = '1001')
    {
        return $this->response->json(compact('msg', 'code'));
    }

    public function jsonSuccess($data = [], $msg = '', $code = 0)
    {
        return $this->response->json(compact('data', 'msg', 'code'));
    }
}