<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/27
 * Time: 8:55
 */

namespace app\controllers\admin\html\form;

class Time extends Input
{
    protected $format = 'yyyy-MM-dd HH:mm:ss';

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <input type="text" class="input-text Wdate" onfocus=" WdatePicker({dateFmt:'{$this->format}'})" name="{$this->attr}" {$this->require} value="{$this->value()}"/>
      </div>
    </div>
html;
        return $this->html;
    }

    /**
     * 时间格式,js的时间格式
     * @param $format
     * @return $this
     * @author Lejianwen
     */
    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}