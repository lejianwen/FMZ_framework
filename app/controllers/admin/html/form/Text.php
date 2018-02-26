<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 9:40
 */

namespace app\controllers\admin\html\form;

class Text extends Input
{

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <input type="text" class="input-text" name="{$this->attr}" {$this->require} value="{$this->value()}"/>
      </div>
    </div>
html;
        return $this->html;
    }

}