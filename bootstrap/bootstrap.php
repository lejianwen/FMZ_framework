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

class bootstrap
{
    public static function start()
    {
        self::init();
        self::database();
        self::exception();
        //cli模式不载入路由
        IS_CLI OR self::route();
    }

    /**定义一些配置
     *
     */
    public static function init()
    {
        define('BASE_PATH', __DIR__.'/../');
        define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
        define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
    }

    public static function exception()
    {
        $whoops = new \Whoops\Run;
        $handler = new \Whoops\Handler\PrettyPageHandler;
        $whoops->pushHandler($handler);

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
                if(is_string($uri))
                    Route::$method($uri, $callback);
                else// if($callback instanceof Closure)
                    Route::$method($callback);
        Route::dispatch();
    }

    public static function view()
    {

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