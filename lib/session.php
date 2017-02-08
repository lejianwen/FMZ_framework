<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/8
 * Time: 9:44
 * QQ: 84855512
 */
namespace lib;
class session
{
    public function get($key = null)
    {
        if($key === null)
            return $_SESSION;
        return $_SESSION[$key];
    }

    public function set($key, $value)
    {
        if($key !== null)
            $_SESSION[$key] = $value;
    }
}