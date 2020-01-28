<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/10/1
 * Time: 22:18
 */

namespace app\controllers\api\admin;

use lib\controller;

class FileController extends controller
{
    public function upload()
    {
        /** @var \lib\file $file */
        $file = request()->file('file');
        $path = '/uploads/' . date('Ymd') . '/';
        $filename = $file->moveUpFile(WEB_ROOT . $path);
        $file_path = $path . $filename;
        return $this->response->json(['code' => 0, 'data' => '//' .env('HTTP_HOST') . $file_path]);
    }
}