<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:15
 */

namespace app\controllers\admin\html\grid;

class Json extends Data
{
    public function __construct($attr)
    {
        parent::__construct($attr);
        $this->display = function ($value, $item) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        };
    }
}