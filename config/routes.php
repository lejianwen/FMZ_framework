<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:54
 * QQ: 84855512
 */

use \Ljw\Route\Route;

Route::space('app\\controllers\\', 'app\\middleware\\');

//后台路由
include_once 'admin_routes.php';

Route::get('', 'IndexController@index');
Route::get('index/middle', 'Index@index', 'IndexController@middle');

Route::error(function () {
    app('response')->setStatus('404');
    echo '404 Not Found!';
});
Route::run();