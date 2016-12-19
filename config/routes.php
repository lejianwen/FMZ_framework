<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:54
 * QQ: 84855512
 */
const CONTROLLER = 'app\\controllers\\';
return [
    'get'  => [
        ''        => 'app\controllers\IndexController@index',
        '(:str)/(:str)' => function ($controller, $method) {
            //匹配pathinfo模式
            $class = CONTROLLER . ucwords($controller) . 'Controller';
            if(!is_object($class))
            {
                $object = new $class;
                if(method_exists($object,$method) && is_callable([$object,$method]))
                    $object->$method();
                else
                    app\controllers\ErrorController::NotFound_404();
            }else
            {
                app\controllers\ErrorController::NotFound_404();
            }
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