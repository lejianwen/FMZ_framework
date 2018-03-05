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
    protected $child_attr;
    protected $origin_attr;

    public function __construct($attr)
    {
        $this->origin_attr = $attr;
        $arr = explode('.', $attr);
        $this->attr = $arr[0];
        if (isset($arr[1])) {
            array_shift($arr);
            $this->child_attr = $arr;
        }
    }

    protected function mRenderReturn()
    {
        return "return {$this->value()}";
    }

    public function value()
    {
        if (empty($this->child_attr)) {
            $js = 'value';
        } else {
            $js = '(';
            $js .= '(value ';
            $temp = 'value';
            foreach ($this->child_attr as $child) {
                $temp .= ".{$child}";
                $js .= " && {$temp}";
            }
            $js .= ") ? {$temp} : value )";
        }
        return $js;
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
        $this->add_js .= $js;
        return $this;
    }

    public function orderAble($able = true)
    {
        $this->order_able = $able ? 'true' : 'false';
        return $this;
    }
}