<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/7
 * Time: 10:44
 * QQ: 84855512
 */
namespace app\models;
use \Illuminate\Database\Eloquent\Model;
class Video extends Model
{
    protected $table = 'gc_menu_video';
    protected $primaryKey = 'video_id';
    /**
     * 获取该视频所在的频道
     */
    public function belongsToManyMenu()
    {
        return $this->belongsToMany('app\models\Menu', 'gc_menu_video_relation', 'video_id', 'menu_id');
    }

    /*public function Menu()
    {
        return $this->morphedByMany('app\models\Menu', '', 'gc_menu_video_relation','video_id', 'menu_id');
    }*/

    public function menus()
    {
        return $this->hasManyThrough('App\Post', 'App\User');
    }
}