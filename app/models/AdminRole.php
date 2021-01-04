<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/3/22
 * Time: 11:06
 * QQ: 84855512
 */

namespace app\models;

use app\models\traits\Date;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use Date;

    protected $guarded = [];
}
