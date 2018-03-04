<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 11:05
 */

namespace app\controllers\admin\html\form;

class Select extends Input
{
    protected $options;
    protected $multiple = '';

    /**
     * 参数
     * @param array $options [['label' => '属性名', 'value' => 1, 'default' => 1], ...]
     * @return $this
     * @author Lejianwen
     */
    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $options_html = '';
        foreach ($this->options as $option) {
            $selected = "";
            if (!empty($this->value)) {
                if ((!$this->multiple && $option['value'] === $this->value)
                    || ($this->multiple && is_array($this->value) && in_array($option['value'], $this->value))
                ) {
                    $selected = 'selected';
                }
            } else {
                $selected = empty($option['default']) ? '' : 'selected';
            }

            $options_html .= '<option value="' . $option['value'] . '" ' . $selected . '>' . $option['label'] . '</option>';
        }
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
         <select name="{$this->attr}" {$this->require} class="select" {$this->multiple}>{$options_html}</select>
      </div>
    </div>
html;
        return $this->html;
    }

    public function isMultiple()
    {
        $this->attr .= '[]';
        $this->multiple = 'multiple';
        return $this;
    }
}