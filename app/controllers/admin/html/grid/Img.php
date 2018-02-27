<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:44
 */

namespace app\controllers\admin\html\grid;

class Img extends Data
{
    protected $max_width;
    protected $max_height;

    public function __construct($attr, $max_width = 50, $max_height = 50)
    {
        $this->attr = $attr;
        $this->max_width = $max_width;
        $this->max_height = $max_height;
    }

    public function mRenderReturn()
    {
        return <<<js
return '<img style="max-width:{$this->max_width}px;max-height:{$this->max_height}px" src="'+value+'" >'
js;
    }
}