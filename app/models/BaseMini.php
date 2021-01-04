<?php
/**
 * Class BaseMini
 * Author lejianwen
 * Date: 2020/3/13 17:25
 */

namespace app\models;


use app\models\traits\Date;
use Illuminate\Database\Eloquent\Model;

class BaseMini extends Model
{
    use Date;

    protected $guarded = [];
    const CACHE_ALL_KEY = '';

    public static function allInfo()
    {
        static $list;
        if (!empty($list)) {
            return $list;
        }
        $list = cache()->get(static::CACHE_ALL_KEY);
        if (!empty($list)) {
            return $list;
        }
        $list = static::query()->where('status', COMMON_STATUS_ENABLE)->orderByDesc('id')->get()->toArray();
        cache()->set(static::CACHE_ALL_KEY, $list);
        return $list;
    }

    public static function findByColumn($column, $value)
    {
        $list = static::allInfo();
        foreach ($list as $key => $item) {
            if ($item[$column] == $value) {
                return $item;
            }
        }
        return [];
    }
}
