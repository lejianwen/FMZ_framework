<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/23
 * Time: 9:15
 */

namespace app\controllers\admin\html;

use app\controllers\admin\html\form\Select;
use app\controllers\admin\html\form\Text;
use app\controllers\admin\html\form\Textarea;

class Form
{
    protected $inputs = [];
    protected $item;

    public function __construct($item = null)
    {
        $this->item = $item;
    }

    public function text($label, $attr)
    {
        $value = '';
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $input = new Text($label, $attr, $value);
        $this->inputs[] = $input;
        return $input;
    }

    public function textarea($label, $attr)
    {
        $value = '';
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $input = new Textarea($label, $attr, $value);
        $this->inputs[] = $input;
        return $input;
    }

    public function select($label, $attr, $options = [])
    {
        $value = '';
        if ($this->item && isset($this->item[$attr])) {
            $value = $this->item[$attr];
        }
        $input = new Select($label, $attr, $value);
        $input->options($options);
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
