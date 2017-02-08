<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:48
 * QQ: 84855512
 */
use Illuminate\Database\Capsule\Manager as Capsule;
use NoahBuscher\Macaw\Macaw as Route;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use lib\http\request;
class bootstrap
{

    public static function start()
    {
        define('APP_START', microtime(true));
        //系统初始化
        self::init();
        //日志
        self::log();
        //session系统
        self::session();
        //数据库配置载入
        self::database();
        //错误信息
        self::exception();
        //cli模式不载入路由
        IS_CLI OR self::route();
        define('APP_EXIT', microtime(true));
    }

    /**定义一些配置
     *
     */
    public static function init()
    {
        //默认时区定义
        date_default_timezone_set('Asia/Shanghai');
        //设置默认区域
        setlocale(LC_ALL, "zh_CN.utf-8");
        //设置根路径
        defined('BASE_PATH') or define('BASE_PATH', __DIR__ . '/../');
        //调试模式
        defined('DEBUG') or define('DEBUG', true);
        //是否是命令行模式
        defined('IS_CLI') or define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        //是否是Ajax请求
        defined('IS_AJAX') or define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);

    }

    public static function log()
    {
        $info_log = new Logger('SYS_INFO');
        $info_log->pushHandler(new StreamHandler(BASE_PATH . 'data/log/system/' . date('Y-m-d') . '.log', Logger::DEBUG));
        $request = request::_instance();
        $info_log->debug('request_info:', [
            'ip' => $request->getClientIp(),
            'method' => $request->getMethod(),
            'url' => $request->getUrl()
        ]);
    }


    /** session扩展
     *  如果要把session存到数据库或别的地方再来完成改方法
     */
    public static function session()
    {
        $session_driver = config('app.session');
        $class = 'lib\session\\' . $session_driver;
        $handler = new $class;
        session_set_save_handler(
            array(&$handler,"open"),
            array(&$handler,"close"),
            array(&$handler,"read"),
            array(&$handler,"write"),
            array(&$handler,"destroy"),
            array(&$handler,"gc"));
        register_shutdown_function('session_write_close');
        session_write_close();
        session_start();
    }

    /**报错提示
     *
     */
    public static function exception()
    {

        $whoops = new \Whoops\Run;
        //错误信息,调试模式打开则显示,否则只记录到日志
        if (DEBUG)
        {
            $handler = new \Whoops\Handler\PrettyPageHandler;
            $whoops->pushHandler($handler);
        }
        $error_log = new Logger('SYS_ERROR');
        $error_log->pushHandler(new StreamHandler(BASE_PATH . 'data/log/system/error.log', Logger::ERROR));

        $handler_log = new \Whoops\Handler\PlainTextHandler($error_log);
        $handler_log->loggerOnly(true);
        $whoops->pushHandler($handler_log);
        $whoops->register();
    }

    //orm 模型
    public static function database()
    {
        // Create a new Database connection
        $capsule = new Capsule;
        $capsule->addConnection(require BASE_PATH . '/config/database.php');
        $capsule->bootEloquent();
    }

    //路由
    public static function route()
    {
        //添加正则匹配字符串
        Route::$patterns[':str'] = '[a-zA-Z0-9_]+';
        $routes = require BASE_PATH . '/config/routes.php';
        foreach ($routes as $method => $route)
            foreach ($route as $uri => $callback)
                if (is_string($uri))
                    Route::$method($uri, $callback);
                else// if($callback instanceof Closure)
                    Route::$method($callback);
        Route::dispatch();
    }

    public static function redis()
    {
        $config = require BASE_PATH . '/config/redis.php';
        static $redis_client;
        if (!$redis_client)
            $redis_client = new Predis\Client($config);
        return $redis_client;
    }
}