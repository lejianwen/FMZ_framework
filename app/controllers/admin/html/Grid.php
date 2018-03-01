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

/**
 * Class Grid
 * @method grid\Text text($attr)
 * @method grid\Json json($attr)
 * @method grid\Img img($attr, $width = 50, $height = 50)
 * @method grid\Select select($attr, $options = [])
 * @method grid\Label label($attr, $class = 'label-success')
 * @method grid\SwitchLabel switchLabel($attr, $options = [])
 * @method grid\Icon icon($attr)
 * @package app\controllers\admin\html
 */
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
    protected $no_search = false;
    /** @var SearchForm */
    protected $search_form;

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

    public function __call($func, $params)
    {
        $class = 'app\\controllers\\admin\\html\\grid\\' . ucfirst($func);
        $obj = new $class(...$params);
        $this->data[] = $obj;
        return $obj;
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
            return ['header' => [], 'jsData' => '', 'js' => '', 'no_add' => $this->no_add, 'search_form' => ''];
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
        return [
            'header'      => $this->header,
            'jsData'      => implode(',', $data_js),
            'js'          => $js,
            'no_add'      => $this->no_add,
            'search_form' => $this->no_search ? '' : $this->search_form->inputs()
        ];
    }

    public function addJs($js)
    {
        $this->js[] = $js;
    }

    public function disAbleAdd()
    {
        $this->no_add = true;
    }

    public function disableSearch()
    {
        $this->no_search = true;
    }

    public function addSearchForm(SearchForm $search_form)
    {
        if ($search_form && !$search_form->isEmpty()) {
            $this->search_form = $search_form;
        }
    }
}