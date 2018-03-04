<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/3/2
 * Time: 13:51
 */

namespace app\controllers\admin\html\form;

class Person extends Input
{
    protected $self_html;

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        {$this->self_html}
      </div>
    </div>
html;
        return $this->html;
    }

    public function setInputHtml(\Closure $closure)
    {
        return $this->self_html = $closure($this);
    }
}