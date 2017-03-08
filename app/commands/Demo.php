<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/8
 * Time: 15:29
 * QQ: 84855512
 */
namespace app\commands;
use app\models\User;

class Demo
{
    public function first()
    {
        echo "test succ \n";
//        echo User::first()->id;
    }
}