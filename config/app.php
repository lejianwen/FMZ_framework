<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/1/10
 * Time: 14:09
 * QQ: 84855512
 */
return [
    //session存放 为空表示用自带的, '' || mysql_pdo || redis || prdis, 具体看lib/session中的文件
    'session'          => 'mysql_pdo',
    'session_table'    => 'session',    //session 存放在mysql中的表名
    'session_lefttime' => 1000,         //session有效时间
    'session_redis_db' => 0,            //session 存放在redis中的某个db
    'debug'            => true,         //调试模式
    'view'             => 'smarty',     //模板 smarty||native
    'smarty'           => [             //smarty配置
        'debug'           => false,     //是否弹出debug窗口
        'force_compile'   => true,     //检查模板是否改动,开发时打开,正式 关闭
        'cache'           => false,     //是否缓存
        'cache_lifetime'  => 120,       //缓存时间
        'cache_dir'       => BASE_PATH . 'runtime/smarty/cache/',          //缓存目录
        'compile_dir'     => BASE_PATH . 'runtime/smarty/templates_c/',    //编译目录
        'left_delimiter'  => '<{',      //左定界符
        'right_delimiter' => '}>'       //右定界符
    ],
    'cache'            => 'file',       //缓存类型，file||redis||predis
    'cache_expire'     => 120,          //缓存时间
    'cache_file_dir'   => BASE_PATH . 'runtime/cache/',
    'cache_redis_db'   => 1


];