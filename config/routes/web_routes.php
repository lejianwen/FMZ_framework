<?php
// web页面 ======================
use Ljw\Route\Route;

Route::space('app\\controllers\\', 'app\\middleware\\');
Route::get('', 'IndexController@index');
