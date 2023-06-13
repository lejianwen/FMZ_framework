<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/11
 * Time: 22:14
 */

use Ljw\Route\Route;

Route::space('app\\controllers\\api\\', 'app\\middleware\\');
//
function adminRouteResource($name)
{
    $controller_name = ucfirst($name) . 'Controller';
    Route::get("{$name}/index", "admin\\{$controller_name}@index");
    Route::get("{$name}/detail", "admin\\{$controller_name}@detail");
    Route::post("{$name}/create", "admin\\{$controller_name}@create");
    Route::post("{$name}/update", "admin\\{$controller_name}@update");
    Route::post("{$name}/delete", "admin\\{$controller_name}@delete");
}

Route::group('/api/admin', function () {
    /** 后台 */
    Route::post('login', 'admin\LoginController@login');
    Route::post('logout', 'admin\LoginController@logout');
    Route::middleware("Admin", function () {
        adminRouteResource('admin');
        adminRouteResource('adminRole');
        adminRouteResource('config');
        adminRouteResource('file');
        Route::post('upPass', 'admin\AdminController@upPass');
        Route::get('info', 'admin\AdminController@info');
        adminRouteResource('adminLog');
        Route::post('adminLog/batchDelete', 'admin\AdminLogController@batchDelete');
        Route::post('file/upload', 'admin\FileController@upload');
    });
});
