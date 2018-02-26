<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/26
 * Time: 8:36
 */

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $guarded = [];

    public function admins()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}