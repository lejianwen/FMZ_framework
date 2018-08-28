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
        $this->attr = '_actions';
        $this->display = function ($id, $item) {
            $html = '';
            if (!$this->disable_edit) {
                $html .= "<span value='{$id}' data-id='{$id}' class='btn btn-primary update'>编辑</span>";
            }
            if (!$this->disable_del) {
                $html .= " <span value='{$id}' data-id='{$id}' class='btn btn-danger delete'>删除</span> ";
            }
            if (!empty($this->append)) {
                $html .= str_replace('{$id}', $id, implode("\t", $this->append));
            }
            return $html;
        };
    }

    public function append($html)
    {
        $this->append[] = $html;
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
}