<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:54
 * QQ: 84855512
 */
return [
    'get'  => [
        ''        => 'app\controllers\IndexController@index',
        'index/index' => 'app\controllers\IndexController@index',
        'index/test' => function (){
            app('response')->json(['a' => 'aaa']);
        },
        //匹配
        '(:str)/(:str)' => function ($controller, $method) {
            $class = 'app\\controllers\\' . ucwords($controller) . 'Controller';
            if (method_exists($class, $method) && is_callable([$class, $method]))
            {
                $object = new $class;
                $object->$method();
            } else
                app\controllers\ErrorController::NotFound_404();
        }
    ],
    'post' => [

    ],
    'error' => [
        function () {
            app\controllers\ErrorController::NotFound_404();
        }
    ]

];