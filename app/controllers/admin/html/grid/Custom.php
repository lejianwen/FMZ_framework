<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/8/26
 * Time: 16:27
 */


namespace app\controllers\admin\html\grid;

class Custom extends Data
{
    protected $callback;

    public function __construct($attr, $callback = null)
    {
        parent::__construct($attr);
        $this->callback = $callback;
    }

    public function mRenderReturn()
    {
        if ($this->callback && $this->callback instanceof \Closure) {
            $return = ($this->callback)($this->value());
            return "return {$return};";
        }
        return "return {$this->value()}";
    }
}