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
Route::get('index/middle', 'Index@index', 'IndexController@middle');

Route::error(function () {
    response()->setStatus('404');
    response()->json('404 not');
//    response()->setContent('404 Not Found!');
});