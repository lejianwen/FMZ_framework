<?php

namespace app\controllers\api;

use Illuminate\Database\Capsule\Manager as DB;

class IndexController extends BaseController
{
    public function swagger()
    {
        $openapi = \Swagger\scan(BASE_PATH . '/app/');
        header('Content-Type: application/json');
        echo $openapi;
        exit;
    }

}
