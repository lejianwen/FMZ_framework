<?php

// api ======================
use Ljw\Route\Route;

Route::space('app\\controllers\\api\\', 'app\\middleware\\');
Route::get('/swagger-api', 'IndexController@swagger');
