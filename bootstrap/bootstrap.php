<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:48
 * QQ: 84855512
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class bootstrap
{

    public static function start()
    {
        self::init();
        // 路由
        \Ljw\Route\Route::run();
        //响应
        response()->send();
        //日志
        self::log();
    }

    /**
     * 初始化
     * @author lejianwen
     */
    public static function init()
    {
        //系统初始化
        self::env();
        //session系统
        IS_CLI || self::session();
        //数据库配置载入
        self::database();
        //错误信息
        self::exception();
        //载入路由
        require_once BASE_PATH . '/config/routes.php';
    }

    public static function log()
    {
        if (config('app.sys_log')) {
            $info_log = new Logger('SYS_LOG');
            $level = config('app.sys_log_level');
            $info_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . date('Y-m') . '/' . date('d') . '.log', $level));
            $request = app('request');
            $info_log->$level('request_info:', [
                'ip' => $request->getClientIp(),
                'method' => $request->method(),
                'uri' => $request->uri()
            ]);
        }
    }


    /** session扩展
     *  如果要把session存到数据库或别的地方再来完成改方法
     */
    public static function session()
    {
        $session_driver = config('app.session');
        if ($session_driver) {
            $class = 'lib\\session\\' . $session_driver;
            $handler = new $class;
            session_set_save_handler(
                array(&$handler, "open"),
                array(&$handler, "close"),
                array(&$handler, "read"),
                array(&$handler, "write"),
                array(&$handler, "destroy"),
                array(&$handler, "gc")
            );
        } else {
            ini_set('session.gc_maxlifetime', config('app.session_lefttime'));
        }
        register_shutdown_function('session_write_close');
    }

    /**报错提示
     *
     */
    public static function exception()
    {
        $whoops = new \Whoops\Run;

        //错误信息,调试模式打开则显示,否则只记录到日志
        if (config('app.debug')) {
            $whoops_handler = new \Whoops\Handler\PrettyPageHandler;
            $whoops->pushHandler($whoops_handler);
        }

        if (config('app.sys_error_log')) {
            $error_log = new Logger('SYS_ERROR');
            $error_log->pushHandler(new StreamHandler(SYSTEM_LOG_PATH . 'error.log', Logger::ERROR));

            $whoops_log_handler = new \Whoops\Handler\PlainTextHandler($error_log);
            $whoops_log_handler->loggerOnly(true);
            $whoops->pushHandler($whoops_log_handler);
        }
        $whoops->register();
    }

    //orm 模型
    public static function database()
    {
        // Create a new Database connection
        $capsule = new Capsule;
        $capsule->addConnection(require BASE_PATH . '/config/database.php');
        //为了能使用 DB::table()这种形式
        //use Illuminate\Database\Capsule\Manager as DB;
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
        //设置web根路径
        defined('WEB_ROOT') or define('WEB_ROOT', BASE_PATH . 'public/');
        //设置runtime路径
        defined('RUNTIME_PATH') or define('RUNTIME_PATH', BASE_PATH . 'runtime/');
        //系统日志路径
        defined('SYSTEM_LOG_PATH') or define('SYSTEM_LOG_PATH', __DIR__ . '/../runtime/log/system/');
        //是否是命令行模式
        defined('IS_CLI') or define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        //是否是Ajax请求
        defined('IS_AJAX') or define('IS_AJAX',
            ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);

        $dotenv = new Dotenv\Dotenv(BASE_PATH);
        $dotenv->load();
    }
}