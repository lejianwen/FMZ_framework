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
        self::database();
        self::exception();
        self::route();
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
        Route::$patterns = array(
            ':any' => '[^/]+',
            ':num' => '[0-9]+',
            ':all' => '.*',
            ':str' => '[a-zA-Z0-9_-]+' //字符串
        );
        $routes = require BASE_PATH . '/config/routes.php';
        foreach ($routes as $method => $route)
            foreach ($route as $uri => $callback)
                if(is_string($uri))
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