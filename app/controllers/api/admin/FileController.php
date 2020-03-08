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
        $file = request()->file('file');
        $path = '/uploads/' . date('Ymd') . '/';
        $name = $file->getClientOriginalName();
        $filename = $file->move(WEB_ROOT . $path, $name);
        return $this->response->json(['code' => 0, 'data' => $path . $name]);
    }
}
