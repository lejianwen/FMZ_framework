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

Route::get('', 'IndexController@index');
Route::get('test', 'IndexController@test');
Route::get('index/middle', 'Index', 'IndexController@middle');

Route::error(function () {
    response()->setStatusCode('404');
    echo '404 Not Found!';
});
