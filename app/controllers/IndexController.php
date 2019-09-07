<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:11
 * QQ: 84855512
 */

namespace app\controllers;

class IndexController extends BaseController
{
    public function index()
    {
        echo "<pre>";
        var_dump($_GET);
        var_dump('get12');
        var_dump(request()->getClientIp());
        $this->response
            ->with(['framework' => 'FMZ_framework'])
            ->view('index/index');
    }

    public function files()
    {
        var_dump('_FILES', $_FILES);
//        exit;
        var_dump('request()->file()', request()->file());
//        $files = request()->file();
//        $files['f1333']->moveUpFile(PUBLIC_PATH . '/upload/', '', true);
//        var_dump('request()->post()', request()->post());
    }

    public function middle($middle_result)
    {
        echo $middle_result;
    }
}
