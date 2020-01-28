<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/19
 * Time: 10:27
 * QQ: 84855512
 */

namespace app\controllers;

use app\models\Config;
use lib\controller;

class BaseController extends controller
{
    public function __construct()
    {
        parent::__construct();

        $this->response->with([
            'site_name' => Config::findByCode('SITE_NAME'),
            'site_host' => Config::findByCode('SITE_HOST'),
            'mobile_host' => Config::findByCode('MOBILE_HOST'),
            'request_uri' => env('REQUEST_URI'),
        ]);
    }

    public function __destruct()
    {
        // 开启静态化
        if (env('OPEN_STATIC') && $this->response->getStatus() == 200) {
            $uri = env('REQUEST_URI');
            if ($uri == '/') {
                $this->response->saveToHtml(WEB_ROOT . 'index.html');
            } elseif (substr($uri, -5) == '.html') {
                $this->response->saveToHtml(WEB_ROOT . $uri);
            }
        }

    }
}
