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

    public function __construct($attr, $options = [])
    {
        $this->attr = $attr;
        $this->options = $options;
    }

    public function mRenderReturn()
    {
        $options = json_encode($this->options);
        return <<<js
            var options = {$options};
            var html = '<select class="select changeAttr" data-id="'+rd.id+'" data-attr="{$this->attr}">';
            $.each(options, function(k,v){
                var selected = '';
                if(v['value'] == value){selected = 'selected';}
                html += '<option value="'+v['value']+'" '+selected+'>' + v['label'] + '</option>';
            })
            html += '</select>';
            return html;
js;
    }

    public function options($options)
    {
        $this->options = $options;
    }
}