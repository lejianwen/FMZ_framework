<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/28
 * Time: 21:33
 */

namespace app\controllers\admin\html\grid;

class Icon extends Data
{
    public function __construct($attr)
    {
        parent::__construct($attr);
        $this->display = function ($value, $item) {
            return "<i class=\"Hui-iconfont\">{$value}</i>";
        };
    }
}