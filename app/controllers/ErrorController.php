<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 9:55
 * QQ: 84855512
 */
namespace app\controllers;
use lib\http\response;
class ErrorController
{
    public static function NotFound_404()
    {
        response::sendHttpStatus('404');
        echo '未匹配到路由<br>';
    }
}