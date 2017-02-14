<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/8
 * Time: 13:51
 * QQ: 84855512
 */
namespace lib;
abstract class view
{
    const VIEW_PATH = BASE_PATH . 'app/views/';
    protected $tpl;
    protected $data;

    public static function _instance()
    {
        static $view;
        if (!$view)
        {
            $class = 'lib\view\\' . config('app.view');
            $view = new $class;
        }
        return $view;
    }

    public function setTpl($tpl)
    {
        if (is_file(self::VIEW_PATH . $tpl))
            $this->tpl = self::VIEW_PATH . $tpl;
        return $this;
    }

    public function with($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function display($tpl = null) { }
}