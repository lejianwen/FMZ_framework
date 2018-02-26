<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/24
 * Time: 22:46
 */

namespace app\controllers\admin\html\form;

class File extends Input
{
    protected $multiple = false;
    protected $accept;

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $multiple = $this->multiple ? 'multiple' : '';
        $accept = $this->accept ?: '*/*';
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <span class="btn-upload form-group">
          <input class="input-text upload-url radius" type="text" readonly disabled value="{$this->value}">
          <a href="javascript:void();" class="btn btn-primary radius">
          <i class="Hui-iconfont">&#xe642;</i> 浏览文件</a>
          <input type="file" {$multiple} name="{$this->attr}" {$this->require} class="input-file" accept="{$accept}">
        </span>
      </div>
    </div>
html;
        return $this->html;
    }

    public function isMultiple()
    {
        $this->multiple = true;
        $this->attr .= '[]';
        return $this;
    }

    public function accept($accept = '*/*')
    {
        $this->accept = $accept;
        return $this;
    }
}