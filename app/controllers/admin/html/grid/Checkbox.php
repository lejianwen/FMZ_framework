<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/9/8
 * Time: 10:23
 */

namespace app\controllers\admin\html\grid;

class Checkbox extends Data
{
    protected $options;
    protected $disabled = false;

    public function __construct($attr, $add_class = '')
    {
        parent::__construct($attr);
        $this->display = function ($value, $item) use ($add_class) {
            $disabled = $this->disabled ? 'disabled' : '';
            $html = "<div class='check-box'><input type='checkbox' class='{$add_class}' data-id='{$item['id']}' data-attr='{$this->attr}' data-value='{$value}' {$disabled}></div>";
            return $html;
        };
    }

    public function disabled()
    {
        $this->disabled = true;
        return $this;
    }
}