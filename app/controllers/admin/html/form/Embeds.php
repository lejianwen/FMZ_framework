<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/3/1
 * Time: 8:22
 */

namespace app\controllers\admin\html\form;

/**
 * Class Embeds
 * @method Select select($label, $attr)
 * @method Input text($label, $attr)
 * @method Textarea textarea($label, $attr)
 * @method File file($label, $attr)
 * @method Image image($label, $attr)
 * @method Hidden hidden($label, $attr)
 * @method Time time($label, $attr)
 * @method Embeds embeds($label, $attr, \Closure $closure)
 * @package app\controllers\admin\html\form
 */
class Embeds extends Input
{
    protected $child;
    protected $attr;
    protected $item;
    protected $inputs;

    public function __construct($label, $attr, \Closure $closure, $value = [])
    {
        $this->attr = $attr;
        $this->item = $value;
        $this->label = $label;
        $closure($this);
    }

    public function __call($func, $params)
    {
        $value = null;
        $attr = $params[1];
        $obj = 'app\\controllers\\admin\\html\\form\\' . ucfirst($func);
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $params[1] = "{$this->attr}[{$attr}]";
        $params[] = $value;
        $input = new $obj(...$params);
        $this->inputs[] = $input;
        return $input;
    }

    public function toHtml()
    {
        $children = implode('', $this->inputs());
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $html = <<<html
<div class="row cl" style="border: #c3c3c3 1px solid;padding: 10px;">
      <label class="form-label col-xs-6">{$required_tag}{$this->label}</label>
      <div class="formControls col-xs-6">
      </div>
      <div class="formControls col-xs-12">
        {$children}
      </div>
</div>
html;
        return $html;
    }

    public function inputs()
    {
        $inputs = [];
        foreach ($this->inputs as $input) {
            $inputs[] = $input->toHtml();
        }
        return $inputs;
    }
}