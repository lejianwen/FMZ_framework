<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 9:38
 */

namespace app\controllers\admin\html\form;

abstract class Input
{
    protected $html;
    protected $require;
    protected $label;
    protected $attr;
    protected $value;

    public function __construct($label, $attr, $value = '')
    {
        $this->label = $label;
        $this->attr = $attr;
        $this->value = $value;
    }

    public function toHtml()
    {
        return $this->html;
    }

    public function required($re = true)
    {
        $this->require = $re ? 'required' : '';
        return $this;
    }
}