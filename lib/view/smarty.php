<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/7
 * Time: 14:46
 * QQ: 84855512
 */
namespace lib\view;
class smarty
{
    const VIEW_PATH = BASE_PATH . 'app/views/';
    protected $smarty;
    protected $tpl;

    public function __construct()
    {
        if (!$this->smarty)
        {
            $smarty = new \Smarty;
            $smarty->setTemplateDir(self::VIEW_PATH);
            $smarty->setCompileDir(BASE_PATH . '/data/smarty/templates_c/');
            $smarty->setConfigDir(BASE_PATH . '/data/smarty/configs/');
            $smarty->setCacheDir(BASE_PATH . '/data/smarty/cache/');
            $smarty->setLeftDelimiter("<{");
            $smarty->setRightDelimiter("}>");
            //$smarty->force_compile = true;
            $smarty->debugging = false;
            $smarty->caching = true;
            $smarty->cache_lifetime = 120;

            $this->smarty = $smarty;
        }
        return $this;
    }

    public static function _instance()
    {
        static $self;
        if (!$self)
            $self = new self();
        return $self;
    }

    public function setTpl($tpl)
    {
        if (!is_file(self::VIEW_PATH . $tpl))
        {
            throw new \Exception($tpl . ' file is not exists!');
        }
        $this->tpl = self::VIEW_PATH . $tpl;
    }

    public function with($key, $value)
    {
        $this->smarty->assign($key, $value);
        return $this;
    }

    public function display($tpl = null)
    {
        if(!empty($tpl))
            $this->tpl = self::VIEW_PATH . $tpl;
        $this->smarty->display($this->tpl);
        unset($this->tpl);
        return $this;
    }

    /*public function __destruct()
    {
        if ($this->tpl)
            $this->smarty->display($this->tpl);
    }*/
}