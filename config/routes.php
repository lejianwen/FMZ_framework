<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:54
 * QQ: 84855512
 */
const CONTROLLERS = 'app\\controllers\\';
return [
    'get'  => [
        ''        => 'app\controllers\IndexController@index',
        '(:str)/(:str)' => function ($controller, $method) {
            $class = CONTROLLERS . ucwords($controller) . 'Controller';
            $object = new $class;
            if(is_object($object))
                if (method_exists($object, $method) && is_callable([$object, $method]))
                    $object->$method();
                else
                    app\controllers\ErrorController::NotFound_404();
            else
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