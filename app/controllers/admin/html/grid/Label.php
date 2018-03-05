<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/26
 * Time: 13:30
 */

namespace app\controllers\admin\html\grid;

class Label extends Data
{
    protected $class;

    public function __construct($attr, $class = 'label-success')
    {
        parent::__construct($attr);
        $this->class = $class;
    }

    public function mRenderReturn()
    {
        return <<<js
return '<span class="label {$this->class}">'+{$this->value()}+'</span>'
js;
    }
}