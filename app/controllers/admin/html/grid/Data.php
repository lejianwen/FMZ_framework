<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:03
 */

namespace app\controllers\admin\html\grid;

abstract class Data
{
    protected $attr;
    protected $order_able = 'false';
    protected $js_data = [];
    protected $add_js;

    public function __construct($attr)
    {
        $this->attr = $attr;
    }

    protected function mRenderReturn()
    {
        return 'return value';
    }

    public function dataToJs()
    {
        $this->js_data = "{
           data: '{$this->attr}',
           orderable: {$this->order_able},
           mRender: function(value, d, rd){{$this->mRenderReturn()};}
        }";
        return $this->js_data;
    }

    public function js()
    {
        return $this->add_js;
    }

    public function addJs($js)
    {
        $this->add_js = $js;
        return $this;
    }

    public function orderAble($able = true)
    {
        $this->order_able = $able ? 'true' : 'false';
        return $this;
    }
}