<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/13
 * Time: 16:32
 * QQ: 84855512
 */
return [
    'host' => '0.0.0.0',
    'port' => '9502',
    'env' => 'dev',  //环境 dev|test|prod
    'process_name' => 'swoole-http',  //swoole 进程名称
    'daemonize' => 0, //以守护进程执行
    'worker_num' => 10, //一般设置为服务器CPU数的1-4倍
    'max_request' => 2048,
    'dispatch_mode' => 3, // https://wiki.swoole.com/wiki/page/277.html
    'task_worker_num' => 10,  //task进程的数量
    'task_ipc_mode' => 3,  //使用消息队列通信，并设置为争抢模式
    'task_max_request' => 2048,  //task进程的最大任务数
    'log_file' => LOG_PATH . 'server.log',  //日志
    'user' => 'www',
    'group' => 'www',

    /**超时时长要大于心跳发送间隔**/
    'heartbeat_check_interval' => 60,        //每隔多久进行一次心跳检测
    'heartbeat_idle_time' => 600,      //超时时长
    'open_ssl' => false,
    'ssl_cert_file' => BASE_PATH . '/config/ssl.pem',
    'ssl_key_file' => BASE_PATH . '/config/ssl.key'

];
