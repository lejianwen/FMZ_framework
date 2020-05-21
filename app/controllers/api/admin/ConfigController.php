<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * QQ: 84855512
 * Date: 2019/10/3
 * Time: 21:05
 */

namespace app\controllers\api\admin;

class ConfigController extends BaseController
{
    protected $filters = ['name' => 'like'];

    protected function afterUpdate($item, $is_create = false)
    {
        /** @see \app\models\Config::CACHE_ALL_KEY */
        cache()->del($this->model::CACHE_ALL_KEY);
    }
}
