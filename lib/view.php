<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/8
 * Time: 13:51
 * QQ: 84855512
 */
namespace lib;
class view
{
    public static function _instance()
    {
        static $view;
        if (!$view)
        {
            $class = 'lib\view\\' . config('app.view');
            $view = new $class;
        }
        return $view;
    }
}