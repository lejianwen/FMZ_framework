<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/22
 * Time: 11:06
 * QQ: 84855512
 */

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $guarded = [];

    public static function statusSelectOptions()
    {
        return [
            ['label' => '启用', 'value' => 1, 'default' => 1],
            ['label' => '禁用', 'value' => 0]
        ];
    }

    public static function statusLabelOptions()
    {
        return [
            ['label' => '启用', 'value' => 1, 'class' => 'label-success'],
            ['label' => '禁用', 'value' => 0]
        ];
    }
}