<?php
/**
 * Class AdminLog
 * Author lejianwen
 * Date: 2020/3/12 14:12
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $guarded = [];
    protected $casts = ['post' => 'json'];
}
