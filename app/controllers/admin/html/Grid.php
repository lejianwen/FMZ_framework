<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * QQ: 84855512
 * Date: 2018/2/23
 * Time: 13:15
 */

namespace app\controllers\admin\html;

use app\controllers\admin\html\grid\Action;
use app\controllers\admin\html\grid\Data;
use app\controllers\admin\html\grid\Img;
use app\controllers\admin\html\grid\Json;
use app\controllers\admin\html\grid\Select;
use app\controllers\admin\html\grid\Text;

class Grid
{
    protected $header;
    protected $grid;
    protected $data;
    protected $options = [];
    protected $js = [];
    protected $no_action = false;
    protected $action;
    protected $no_add = false;

    public function __construct()
    {
//        $this->grid = $grid;
    }

    public function setHeader($header = [])
    {
        $this->header = $header;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function img($attr, $width = 50, $height = 50)
    {
        $img = new Img($attr, $width, $height);
        $this->data[] = $img;
        return $this;
    }

    public function text($attr)
    {
        $text = new Text($attr);
        $this->data[] = $text;
        return $text;
    }

    public function json($attr)
    {
        $json = new Json($attr);
        $this->data[] = $json;
        return $json;
    }


    public function select($attr, $options = [])
    {
        $select = new Select($attr, $options);
        $this->data[] = $select;
        return $select;
    }

    public function action()
    {
        if (!$this->action) {
            $action = new Action();
            $this->action = $action;
        }
        return $this->action;
    }

    public function disAbleAction()
    {
        $this->no_action = true;
    }

    public function toHtml()
    {
        if (empty($this->header)) {
            return ['header' => [], 'jsData' => '', 'js' => '', 'no_add' => $this->no_add];
        }
        $data_js = [];
        /** @var Data $data */
        foreach ($this->data as $data) {
            $data_js[] = $data->dataToJs();
            $this->js[] = $data->js();
        }
        if (!$this->no_action) {
            if (!$this->action) {
                $this->action();
            }
            $this->header[] = '操作';
            $data_js[] = $this->action->dataToJs();
        }
        $js = !empty($this->js) ? implode("\n", $this->js) : '';
        return ['header' => $this->header, 'jsData' => implode(',', $data_js), 'js' => $js, 'no_add' => $this->no_add];
    }

    public function addJs($js)
    {
        $this->js[] = $js;
    }

    public function disAbleAdd()
    {
        $this->no_add = true;
    }
}