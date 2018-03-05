<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:06
 */

namespace app\controllers\admin\html\grid;

class Button extends Data
{
    protected $label;
    protected $class_name;

    public function __construct($attr, $label = '', $class_name = '')
    {
        parent::__construct($attr);
        $this->label = $label;
        $this->class_name = $class_name;
    }

    public function label($label)
    {
        $this->label = $label;
    }

    public function mRenderReturn()
    {
        return <<<js
              return '<span value="' + {$this->value()} + '" data-id="' + rd.id + '" class="btn {$this->class_name}">{$this->label}</span>'
js;
    }
}