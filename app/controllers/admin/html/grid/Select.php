<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:16
 */

namespace app\controllers\admin\html\grid;

class Select extends Data
{
    protected $options;
    protected $disabled = false;

    public function __construct($attr, $options = [])
    {
        parent::__construct($attr);
        $this->options = $options;
        $this->display = function ($value, $item) {
            $disabled = $this->disabled ? 'disabled' : '';
            $html = "<select class='select changeAttr' data-id='{$item['id']}' data-attr='{$this->attr}' {$disabled}>";
            foreach ($this->options as $option) {
                $selected = $option['value'] == $value ? 'selected' : '';
                $html .= "<option value='{$option['value']}' {$selected}>{$option['label']}</option>";
            }
            $html .= '</select>';
            return $html;
        };
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function disabled()
    {
        $this->disabled = true;
        return $this;
    }
}