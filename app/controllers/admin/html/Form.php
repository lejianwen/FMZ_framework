<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 9:15
 */

namespace app\controllers\admin\html;

use app\controllers\admin\html\form\Embeds;


/**
 * Class Form
 * @method form\Select select($label, $attr)
 * @method form\Input text($label, $attr)
 * @method form\Textarea textarea($label, $attr)
 * @method form\File file($label, $attr)
 * @method form\Image image($label, $attr)
 * @method form\Hidden hidden($label, $attr)
 * @method form\Time time($label, $attr)
 * @method form\Embeds embeds($label, $attr, \Closure $closure)
 * @package app\controllers\admin\html
 */
class Form
{
    protected $inputs = [];
    protected $item;

    public function __construct($item = null)
    {
        $this->item = $item;
    }

    /*public function embeds($attr, \Closure $closure)
    {
        $value = [];
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $embeds = new Embeds($attr, $closure, $value);
        $this->inputs[] = $embeds;
        return $embeds;
    }*/

    public function __call($func, $params)
    {
        $value = null;
        $attr = $params[1];
        $obj = 'app\\controllers\\admin\\html\\form\\' . ucfirst($func);
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $params[] = $value;
        $input = new $obj(...$params);
        $this->inputs[] = $input;
        return $input;
    }

    public function inputs()
    {
        $inputs = [];
        foreach ($this->inputs as $input) {
            $inputs[] = $input->toHtml();
        }
        return $inputs;
    }

}
