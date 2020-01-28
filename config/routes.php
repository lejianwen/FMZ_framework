<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/12
 * Time: 10:54
 * QQ: 84855512
 */
//后台路由
include_once CONFIG_PATH . 'routes/admin_routes.php';

use \Ljw\Route\Route;

include_once CONFIG_PATH . 'routes/api_routes.php';

include_once CONFIG_PATH . 'routes/web_routes.php';
//会匹配最后一个Route space
Route::error('IndexController@error');
