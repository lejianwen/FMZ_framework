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
class Menu extends Model
{
    protected $table = 'gc_menu';
    protected $primaryKey = 'menu_id';
}