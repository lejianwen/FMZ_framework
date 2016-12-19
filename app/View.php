<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/7
 * Time: 14:46
 * QQ: 84855512
 */
namespace app;
class View
{
    const VIEW_PATH = __DIR__ . '/views/';
    protected $smarty;
    protected $tpl;

    public function __construct($tpl)
    {
        if (!$this->smarty)
        {
            $smarty = new \Smarty;
            $smarty->setTemplateDir(self::VIEW_PATH);
            $smarty->setCompileDir(BASE_PATH . '/smarty/templates_c/');
            $smarty->setConfigDir(BASE_PATH . '/smarty/configs/');
            $smarty->setCacheDir(BASE_PATH . '/smarty/cache/');
            $smarty->setLeftDelimiter("<{");
            $smarty->setRightDelimiter("}>");
            //$smarty->force_compile = true;
            $smarty->debugging = false;
            $smarty->caching = true;
            $smarty->cache_lifetime = 120;

            $this->smarty = $smarty;
            $this->tpl = $tpl;
        }
        return $this;
    }

    public static function make($tpl)
    {
        if (!is_file(self::VIEW_PATH . $tpl))
        {
            throw new \UnexpectedValueException('视图文件不存在');
        } else
        {
            return new View($tpl);
        }
    }

    public function with($key, $value)
    {
        $this->smarty->assign($key, $value);
        return $this;
    }

    public function display()
    {
        $this->smarty->display($this->tpl);
        unset($this->tpl);
        return $this;
    }

    protected function __destruct()
    {
        if ($this->tpl)
            $this->smarty->display($this->tpl);
    }
}