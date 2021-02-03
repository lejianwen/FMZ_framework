<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/10/1
 * Time: 22:18
 */

namespace app\controllers\api\admin;

use app\helpers\OSS;
use app\models\File;

class FileController extends BaseController
{
    public function upload()
    {
        $data = $this->request->post();
        $file = $this->request->file('file');
        $path = 'uploads/' . date('Ymd') . '/';
        $name = $file->getClientOriginalName();
        $filename = $file->move(WEB_ROOT . $path, $name);
        $data['filename'] = $path . $name;
        if (empty($data['host'])) {
            $data['host'] = env('FILE_HOST');
        }

        File::create($data);
        return $this->response->json(['code' => 0, 'data' => $data['host'] . '/' . $path . $name, 'filename' => $filename->getFilename()]);
    }

    public function getOssToken()
    {
        $token = OSS::getToken('test', [
            'origin_filename' => '${x:origin_filename}'
        ]);
        return $this->response->json(['code' => 200, 'data' => $token]);
    }
}
