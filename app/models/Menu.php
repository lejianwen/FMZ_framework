<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/28
 * Time: 19:40
 */

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public static function parentMenus()
    {
        return Menu::query()->where('pid', 0)->get()->toArray();
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'pid', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'id', 'pid');
    }

    public static function allMenus()
    {
        $menus = Menu::with('children')
            ->where('pid', 0)
            ->where('status', 1)
            ->get()
            ->toArray();
        return $menus;
    }
}