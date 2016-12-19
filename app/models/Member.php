<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/6
 * Time: 18:09
 * QQ: 84855512
 */
namespace app\models;
use \Illuminate\Database\Eloquent\Model;
class Member extends Model
{
    protected $table = 'member';

    public function hasManyOrders()
    {
        return $this->hasMany('app\models\Order','cust_no','custNo');
    }
}