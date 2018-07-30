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

    public function fetch($tpl = null)
    {
        if (!empty($tpl)) {
            $this->setTpl($tpl);
        }
        if (!empty($this->data)) {
            extract($this->data);
        }
        ob_start();
        require realpath($this->tpl);
        $content = ob_get_contents();
//        ob_end_flush();
        ob_end_clean();
        return $content;
    }
}