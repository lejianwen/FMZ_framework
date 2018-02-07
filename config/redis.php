<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/11/18
 * Time: 10:43
 */
//redis的配置
return [
    'default' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'pwd' => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', 6379),
        'database' => 0,
    ]

];