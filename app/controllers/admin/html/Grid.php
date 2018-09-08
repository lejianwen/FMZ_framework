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
use app\controllers\admin\html\grid\Checkbox;
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
 * @method grid\Checkbox checkbox($attr)
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
    //批处理
    protected $batch;
    protected $no_batch = false;

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
        $this->data[$params[0]] = $obj;
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

    /**
     * 列表页 输出到模板的部分
     * @return array
     * @author Lejianwen
     */
    public function toHtml()
    {
        if (empty($this->header)) {
            return ['header' => [], 'jsData' => '', 'js' => '', 'no_add' => $this->no_add, 'search_form' => ''];
        }
        //dataTable js需要的json串
        $data_js = [];
        if (!$this->no_batch) {
            array_unshift($this->header, '<div class="check-box"><input type="checkbox" class="checkAll"></div>');
            $this->addBatch('删除', 'batchDelete');
        }
        /** @var Data $data */
        foreach ($this->data as $data) {
            $data_js[] = $data->mRenderReturn();
            $this->js[] = $data->js();
        }
        if (!$this->no_action) {
            if (!$this->action) {
                $this->action();
            }
            $this->header[] = '操作';
            $data_js[] = $this->action->mRenderReturn();
        }
        $js = !empty($this->js) ? implode("\n", $this->js) : '';
        return [
            'header'      => $this->header,
            'jsData'      => implode(',', $data_js),
            'js'          => $js,
            'no_add'      => $this->no_add,
            'search_form' => $this->no_search ? '' : $this->search_form->inputs(),
            'no_batch'    => $this->no_batch,
            'batch'       => $this->batch
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

    public function displayData($list)
    {
        $re = [];
        if (empty($list) || empty($this->data)) {
            return [];
        }
        if (!$this->no_action && !$this->action) {
            $this->action();
        }
        foreach ($list as $key => $item) {
            foreach ($this->data as $attr => $da) {
                if (empty($da->getChildAttr())) {
                    $re[$key][$attr] = ($da->toShow())($item[$attr], $item);
                } else {
                    // 暂时只支持一个层关联
                    $attr = $da->getAttr();
                    $child_attr = current($da->getChildAttr());
                    if (isset($item[$attr])) {
                        $re[$key][$attr][$child_attr] = ($da->toShow())($item[$attr][$child_attr], $item);
                    } else {
                        $re[$key][$attr] = [];
                    }
                }
            }
            if (!$this->no_action) {
                //后面的操作展示
                $re[$key][$this->action->getAttr()] = ($this->action->toShow())($item['id'], $item);
            }
        }
        return $re;
    }

    public function disableBatch()
    {
        $this->no_batch = false;
        return $this;
    }

    public function addBatch($label, $route)
    {
        $this->batch[] = compact('label', 'route');
        return $this;
    }
}