<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/11
 * Time: 22:14
 */

use \Ljw\Route\Route;

Route::space('app\\controllers\\api\\', 'app\\middleware\\');
//
function adminRouteResource($name)
{
    $controller_name = ucfirst($name) . 'Controller';
    Route::get("api/admin/{$name}/index", "admin\\{$controller_name}@index");
    Route::get("api/admin/{$name}/detail", "admin\\{$controller_name}@detail");
    Route::post("api/admin/{$name}/create", "admin\\{$controller_name}@create");
    Route::post("api/admin/{$name}/update", "admin\\{$controller_name}@update");
    Route::post("api/admin/{$name}/delete", "admin\\{$controller_name}@delete");
}

//
/** 后台 */
Route::post('api/admin/login', 'admin\LoginController@login');
Route::post('api/admin/logout', 'admin\LoginController@logout');
Route::middleware("Admin", function () {
    adminRouteResource('admin');
    adminRouteResource('adminRole');
    adminRouteResource('config');
    adminRouteResource('file');
    Route::post('api/admin/upPass', 'admin\AdminController@upPass');
    Route::get('api/admin/info', 'admin\AdminController@info');
    adminRouteResource('adminLog');
    Route::post('api/admin/adminLog/batchDelete', 'admin\AdminLogController@batchDelete');
});
Route::post('api/admin/file/upload', 'admin\FileController@upload');



