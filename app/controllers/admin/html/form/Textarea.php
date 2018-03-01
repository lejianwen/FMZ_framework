<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 9:45
 */

namespace app\controllers\admin\html\form;

class Textarea extends Input
{
    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <textarea type="text" class="textarea" name="{$this->attr}" {$this->require}>{$this->value()}</textarea>
      </div>
    </div>
html;
        return $this->html;
    }
}