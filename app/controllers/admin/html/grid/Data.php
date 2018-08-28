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
    protected $display;

    public function __construct($attr)
    {
        $this->origin_attr = $attr;
        $arr = explode('.', $attr);
        $this->attr = $arr[0];
        if (isset($arr[1])) {
            array_shift($arr);
            $this->child_attr = $arr;
        }
        $this->display = function ($value, $item) {
            return $value;
        };
    }

    public function mRenderReturn()
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
        return "{
           data: '{$this->attr}',
           orderable: {$this->order_able},
           mRender: function(value, d, rd){return {$js};}
        }";
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

    public function display($callback = null)
    {
        if ($callback && $callback instanceof \Closure) {
            $this->display = $callback;
        }
        return $this;
    }

    public function toShow()
    {
        return $this->display;
    }
}