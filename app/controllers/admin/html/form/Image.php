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
    protected $multiple = '';
    protected $accept;

    public function toHtml()
    {
        $required_tag = $this->require ? '<span class="c-red">*</span>' : '';
        $accept = $this->accept ?: 'image/*';
        $image_id = 'image_' . uniqid() . mt_rand(1000, 9999);
        $value = json_encode(((array)$this->value() ?: []));
        $this->html = <<<html
<div class="row cl">
      <label class="form-label col-xs-3">{$required_tag}{$this->label}:</label>
      <div class="formControls col-xs-7">
        <span class="btn-upload form-group" style="height: auto">
          <a href="javascript:void();" class="btn btn-primary radius">
          <i class="Hui-iconfont">&#xe642;</i> 浏览文件</a>
          <input type="file" name="{$this->attr}" {$this->require} {$this->multiple} class="input-file" accept="{$accept}" id="{$image_id}">
        </span>
      </div>
</div>
<script>
$(function(){
  var old_images = {$value}
  if(old_images){
    var cl = $('#{$image_id}').parent();
    old_images.forEach(function(v,k){
      cl.prepend('<img src="' + v + '" style="max-width: 150px;" class="preview_img">')
    })
  };
  
  $('#{$image_id}').change(function(){
      var cl = $(this).parent()
      cl.find('img').remove();
      var reader = [];
      $.each(this.files, function (k, v) {
        reader[k] = new FileReader()
        reader[k].onload = function (e) {
          var img = this.result
          cl.prepend('<img src="' + img + '" style="max-width: 150px;" class="preview_img">')
        }
        reader[k].readAsDataURL(v)
      })
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

    public function isMultiple()
    {
        $this->attr .= '[]';
        $this->multiple = 'multiple';
        return $this;
    }
}