<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/24
 * Time: 22:46
 */

namespace app\controllers\admin\html\form;

class Image extends Input
{
    protected $multiple = false;
    protected $accept;

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $accept = $this->accept ?: 'image/*';
        $image_id = 'image_' . uniqid() . mt_rand(1000, 9999);
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <span class="btn-upload form-group" style="height: auto">
          <img src="{$this->value}" style="max-width: 150px;">
          <a href="javascript:void();" class="btn btn-primary radius">
          <i class="Hui-iconfont">&#xe642;</i> 浏览文件</a>
          <input type="file" name="{$this->attr}" {$this->require} class="input-file" accept="{$accept}" id="{$image_id}">
        </span>
      </div>
</div>
<script>
$(function(){
  $('#{$image_id}').change(function(){
    var image = $(this).parent().find('img');
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = this.result;
        image.attr('src', img);
     }
    reader.readAsDataURL(this.files[0]);
  })
})
</script>

html;
        return $this->html;
    }

    public function accept($accept = '*/*')
    {
        $this->accept = $accept;
        return $this;
    }
}