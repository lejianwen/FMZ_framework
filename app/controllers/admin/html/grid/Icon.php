<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/28
 * Time: 21:33
 */

namespace app\controllers\admin\html\grid;

class Icon extends Data
{
    public function mRenderReturn()
    {
        return <<<js
return '<i class="Hui-iconfont">'+value+'</i>'
js;
    }
}