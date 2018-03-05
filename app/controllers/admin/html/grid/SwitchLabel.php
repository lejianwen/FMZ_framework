<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/26
 * Time: 13:30
 */

namespace app\controllers\admin\html\grid;

class SwitchLabel extends Data
{
    protected $options;
    //是否点击自动转换
    protected $disable_change = false;

    public function __construct($attr, $options = [])
    {
        parent::__construct($attr);
        $this->options($options);
    }

    public function mRenderReturn()
    {
        $options = json_encode($this->options);
        if (!$this->disable_change) {
            $this->initJs();
        }
        return <<<js
var options = {$options};
var labels = {};
var _value = {$this->value()};
$.each(options, function(k,v){
  labels[v.value] = v;
});
return '<span class="label '+labels[_value].class+' switchLabel-{$this->origin_attr}" data-id="'+rd.id+'" data-value="'+_value+'" data-attr="{$this->origin_attr}">'+labels[_value].label+'</span>'
js;
    }

    public function initJs()
    {
        $options = json_encode($this->options);
        $this->add_js = <<<script
(function(){
    var options = {$options};
    var labels = {};
    $.each(options, function(k,v){
      labels[v.value] = v;
    });
    $('#lists').on('click', '.switchLabel-{$this->origin_attr}', function(){
      var that = $(this)
      var _class = $('#_class').val();
      var id = $(this).data('id');
      var to_change = {};
      var value = $(this).data('value');
      var attr = $(this).data('attr');
      $.each(options, function(k,v){
          if(v.value != value){
             to_change = v;
          }
      });
      console.log(to_change);
      postData('/admin/' + _class + '/changeAttr/' + id, {attr: attr, value: to_change.value}, function(){
        that.removeClass(labels[value].class).addClass(to_change.class)
        .attr('data-value', to_change.value).data('value', to_change.value)
        .text(to_change.label);
      })
    }) 
})();
script;
    }

    /**
     * options
     * @param array $options 格式 [['value' => '1', 'class' => 'label-danger', 'label' => 'xxx'], ...]
     * @return $this
     * @author Lejianwen
     */
    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function disabled()
    {
        $this->disable_change = true;
        return $this;
    }

}