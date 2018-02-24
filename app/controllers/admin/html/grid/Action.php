<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/24
 * Time: 9:25
 */

namespace app\controllers\admin\html\grid;

class Action extends Data
{
    protected $append = [];
    protected $disable_edit = false;
    protected $disable_del = false;

    public function __construct()
    {
        $this->attr = 'id';
    }

    public function append($html)
    {
        $this->append[] = "'{$html}'";
        return $this;
    }

    public function disAbleEdit()
    {
        $this->disable_edit = true;
        return $this;
    }

    public function disAbleDel()
    {
        $this->disable_del = true;
        return $this;
    }

    public function mRenderReturn()
    {
        $edit = '""';
        if (!$this->disable_edit) {
            $edit_btn = new Button('id', '修改', 'btn-primary update');
            $edit = str_replace('return', '', $edit_btn->mRenderReturn());
        }
        $del = '""';
        if (!$this->disable_del) {
            $del_btn = new Button('id', '删除', 'btn-danger delete');
            $del = str_replace('return', '', $del_btn->mRenderReturn());
        }
        $append = '""';
        if (!empty($this->append)) {
            $append = implode('+ " " +', $this->append);
        }
        return <<<js
              return $edit + " " + $del + " " +$append;
js;
    }
}