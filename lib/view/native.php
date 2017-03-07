<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/8
 * Time: 13:51
 * QQ: 84855512
 */
namespace lib\view;

use lib\view;

class native extends view
{

    public function display($tpl = null)
    {
        if (!empty($tpl))
            $this->setTpl($tpl);
        extract($this->data);
        require realpath($this->tpl);
        return $this;
    }
}