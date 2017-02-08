<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/1/10
 * Time: 14:09
 * QQ: 84855512
 */
return [
    'session' => 'mysql_pdo', //session存放 file || mysql || redis
    'session_table' => 'session', //session 存放在mysql中的表名
    'view'    => 'smarty' //模板
];