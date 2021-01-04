<?php


namespace app\models;


use app\models\traits\Date;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Date;

    protected $guarded = [];
}
