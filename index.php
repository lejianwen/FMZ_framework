<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/11/17
 * Time: 17:34
 */
define('APP_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
bootstrap::start();
define('APP_EXIT', microtime(true));
ECHO APP_EXIT-APP_START;