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
    protected $width;
    protected $height;

    public function __construct($attr, $width = 50, $height = 50)
    {
        $this->attr = $attr;
        $this->width = $width;
        $this->height = $height;
    }

    public function mRenderReturn()
    {
        return <<<js
              return '<img width='{$this->width}' height='{$this->height}' src='+value+' />'
js;
    }
}