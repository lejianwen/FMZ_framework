<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/11
 * Time: 22:14
 */

use \Ljw\Route\Route;

function adminRouteResource($name)
{
    $controller_name = ucfirst($name) . 'Controller';
    Route::get("admin/$name/index", "admin\\$controller_name@index");
    Route::get("admin/$name/lists", "admin\\$controller_name@lists");
    Route::get("admin/$name/add", "admin\\$controller_name@add");
    Route::post("admin/$name/add", "admin\\$controller_name@add_post");
    Route::get("admin/$name/(:num)", "admin\\$controller_name@update");
    Route::post("admin/$name/(:num)", "admin\\$controller_name@update_post");
    Route::post("admin/$name/changeAttr/(:num)", "admin\\$controller_name@changeAttr");
    Route::post("admin/$name/delete", "admin\\$controller_name@delete");
    Route::post("admin/$name/batchDelete", "admin\\$controller_name@batchDelete");
}

/** 后台 */
Route::get('admin/index', 'admin\IndexController@index');
Route::get('admin/index/welcome', 'admin\IndexController@welcome');
Route::get('admin/login/index', 'admin\LoginController@index');
Route::get('admin/logout', 'admin\LoginController@logout');
Route::post('admin/login', 'admin\LoginController@login_post');
Route::get('admin/admin/index', 'admin\AdminController@index');
Route::get('admin/admin/add', 'admin\AdminController@add');
Route::post('admin/admin/add', 'admin\AdminController@add_post');
Route::get('admin/admin/(:num)', 'admin\AdminController@update');
Route::post('admin/admin/(:num)', 'admin\AdminController@update_post');
Route::post('admin/admin/delete', 'admin\AdminController@delete');
adminRouteResource('menu');
adminRouteResource('user');
adminRouteResource('test');