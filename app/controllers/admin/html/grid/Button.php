<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:06
 */

namespace app\controllers\admin\html\grid;

class Button extends Data
{
    protected $label;
    protected $class_name;

    public function __construct($attr, $label = '', $class_name = '')
    {
        parent::__construct($attr);
        $this->label = $label;
        $this->class_name = $class_name;
        $this->display = function ($value, $item) {
            return "<span value='{$value}' class='btn {$this->class_name}'>{$this->label}</span>";
        };
    }

    public function label($label)
    {
        $this->label = $label;
    }
}