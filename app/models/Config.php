<?php

namespace app\models;


use app\models\traits\Date;

class Config extends BaseMini
{
    use Date;

    const CACHE_ALL_KEY = 'all:config';

    public static function findByCode($code)
    {
        $item = self::findByColumn('code', $code);
        return $item ? $item['value'] : null;
    }
}

/**
 * @SWG\Definition(
 *     definition="Config",
 *     type="object",
 *     @SWG\Property(property="id",type="number", description="id"),
 *     @SWG\Property(property="name",type="string", description="名称",example="网站名"),
 *     @SWG\Property(property="code",type="string", description="配置码",example="SITE_NAME"),
 *     @SWG\Property(property="value",type="string", description="配置值",example="淘宝客网站"),
 *     @SWG\Property(property="status",type="number", description="状态 1启用， 2 禁用",example="1"),
 *     @SWG\Property(property="created_at",type="string", description="创建时间", example="2019-10-10 10:11:12"),
 *     @SWG\Property(property="updated_at",type="string", description="更新时间", example="2019-10-10 10:11:12")
 * )
 */
