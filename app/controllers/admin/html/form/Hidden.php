<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/26
 * Time: 10:59
 */

namespace app\controllers\admin\html\form;

class Hidden extends Input
{

    public function toHtml()
    {
        $this->html = <<<html
        <input type="hidden" name="{$this->attr}" value="{$this->value()}"/>
html;
        return $this->html;
    }

}