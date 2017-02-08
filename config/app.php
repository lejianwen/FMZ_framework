<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/1/10
 * Time: 14:09
 * QQ: 84855512
 */
return [
    'session' => '', //session存放 为空表示用自带的, mysql_pdo || redis, 具体看lib/session中的文件
    'session_table' => 'session', //session 存放在mysql中的表名
    'view'    => 'smarty', //模板
    'smarty' => [
        'debug'           => false,     //是否弹出debug窗口
        'force_compile'   => false,     //检查模板是否改动,开发时打开,正式 关闭
        'cache'           => false,     //是否缓存
        'cache_lifetime'  => 120,       //缓存时间
        'cache_dir'       => BASE_PATH . '/data/smarty/cache/',
        'compile_dir'     => BASE_PATH . '/data/smarty/templates_c/',
        'left_delimiter'  => '<{',
        'right_delimiter' => '}>'
    ],
];