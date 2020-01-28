<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/8
 * Time: 14:50
 * QQ: 84855512
 * CLI模式启动文件
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class console
{
    public static function start()
    {
        //系统初始化
        self::init();
        //运行
        self::run();
        //日志
        self::log();
    }

    /**
     * 定义一些配置
     */
    public static function init()
    {
        //环境配置
        self::env();
        //数据库配置载入
        self::database();
        //错误信息
        self::exception();
    }

    public static function log()
    {
        if (config('app.sys_log')) {
            $info_log = new Logger('SYS_INFO');
            $info_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . date('Y-m') . '/' . date('d') . '.log',
                Logger::DEBUG));
            $info_log->debug('console_info:', $_SERVER['argv']);
        }
    }

    /**报错提示
     *
     */
    public static function exception()
    {
        $whoops = new \Whoops\Run;

        if (config('app.sys_error_log')) {
            $error_log = new Logger('SYS_ERROR');
            $error_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . 'cli_error.log', Logger::ERROR));

            $whoops_log_handler = new \Whoops\Handler\PlainTextHandler($error_log);
//            $whoops_log_handler->loggerOnly(true);
            $whoops->pushHandler($whoops_log_handler);
        }
        $whoops->register();
    }

    public static function run()
    {
        $argv = $_SERVER['argv'];
        $cmd = explode('/', $argv[1]);
        $params = array_slice($cmd, 2);
        $class = 'app\\commands\\' . ucwords($cmd[0]);
        $obj = new $class;
        if (!empty($params)) {
            $obj->{$cmd[1]}(...$params);
        } else {
            $obj->{$cmd[1]}();
        }
    }

    //orm 模型
    public static function database()
    {
        // Create a new Database connection
        $capsule = new Capsule;
        $capsule->addConnection(require BASE_PATH . '/config/database.php');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    //载入配置
    public static function env()
    {
        //默认时区定义
        date_default_timezone_set('Asia/Shanghai');
        //设置默认区域
        setlocale(LC_ALL, "zh_CN.utf-8");
        //设置根路径
        defined('BASE_PATH') or define('BASE_PATH', __DIR__ . '/../');
        defined('CONFIG_PATH') or define('CONFIG_PATH', __DIR__ . '/../config/');
        //设置web根路径
        defined('WEB_ROOT') or define('WEB_ROOT', BASE_PATH . 'public/');
        //设置runtime路径
        defined('RUNTIME_PATH') or define('RUNTIME_PATH', BASE_PATH . 'runtime/');
        //系统日志路径
        defined('SYSTEM_LOG_PATH') or define('SYSTEM_LOG_PATH', __DIR__ . '/../runtime/log/system/');
        //是否是命令行模式
        defined('IS_CLI') or define('IS_CLI', true);
        //是否是Ajax请求
        defined('IS_AJAX') or define('IS_AJAX', false);

        $dotenv = new Dotenv\Dotenv(BASE_PATH);
        $dotenv->load();
    }
}
