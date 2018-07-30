<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:15
 */

namespace app\controllers\admin\html\grid;

class Json extends Data
{
    public function mRenderReturn()
    {
        return <<<js
                  var html = '';
                  if({$this->value()}){
                      $.each({$this->value()}, function (k, v) {
                        html += k + ' : ' + v + '<br>';
                      });
                      return html;
                  }else{
                      return '';
                  }  
js;
    }
}