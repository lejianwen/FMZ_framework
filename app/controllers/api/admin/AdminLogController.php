<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/9/30
 * Time: 21:34
 */

namespace app\controllers\api\admin;


class AdminLogController extends BaseController
{
    protected $filters = ['uri', 'method', 'admin_id'];

    public function batchDelete()
    {
        $ids = $this->request->post('ids');
        if (!$ids) {
            return $this->jsonError();
        }
        $ids = json_decode($ids, true);
        if (empty($ids) || !is_array($ids)) {
            return $this->jsonError();
        }
        $this->model::query()->whereIn('id', $ids)->delete();
        return $this->jsonSuccess();
    }
}
