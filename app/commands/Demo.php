<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/8
 * Time: 15:29
 * QQ: 84855512
 */
namespace app\commands;

class Demo
{
    //php console demo/first/aaa/bbb/ccc
    public function first($a, $b, $c)
    {
        echo "test succ \n";
        var_dump($a, $b, $c);
    }
}