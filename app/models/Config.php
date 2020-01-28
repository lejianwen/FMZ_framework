<?php

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    public static function allInfo()
    {
        static $list;
        if (!empty($list)) {
            return $list;
        }
        $list = cache()->get('all:config');
        if (!empty($list)) {
            return $list;
        }
        $list = self::query()->where('status', COMMON_STATUS_ENABLE)->orderByDesc('id')->get()->toArray();
        cache()->set('all:config', $list);
        return $list;
    }

    public static function findByCode($code)
    {
        $item = self::findByColumn('code', $code);
        return $item ? $item['value'] : null;
    }

    public static function findByColumn($column, $value)
    {
        $list = self::allInfo();
        foreach ($list as $key => $item) {
            if ($item[$column] == $value) {
                return $item;
            }
        }
        return [];
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
}
