<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 9:55
 * QQ: 84855512
 */
namespace app\controllers;
class ErrorController
{
    public static function NotFound_404()
    {
        header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
        echo '未匹配到路由<br>';
    }
}