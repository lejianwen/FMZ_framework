<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2016/12/7
 * Time: 14:46
 * QQ: 84855512
 */
namespace lib\view;

use lib\view;

class smarty extends view
{
    protected $smarty;

    public function __construct()
    {
        if (!$this->smarty)
        {
            $smarty = new \Smarty;
            $smarty->setTemplateDir(self::VIEW_PATH);
            $smarty->setCompileDir(config('app.smarty.compile_dir'));
//            $smarty->setConfigDir(BASE_PATH . '/data/smarty/configs/');
            $smarty->setCacheDir(config('app.smarty.cache_dir'));
            $smarty->setLeftDelimiter(config('app.smarty.left_delimiter'));
            $smarty->setRightDelimiter(config('app.smarty.right_delimiter'));
            $smarty->force_compile = config('app.smarty.force_compile');
            $smarty->debugging = config('app.smarty.debug');
            $smarty->caching = &config('app.smarty.cache');
            $smarty->cache_lifetime = config('app.smarty.cache_lefttime');
            $this->smarty = $smarty;
        }
        return $this;
    }

    public function with($key, $value)
    {
        $this->smarty->assign($key, $value);
        return $this;
    }

    public function display($tpl = null)
    {
        if (!empty($tpl))
            $this->tpl = self::VIEW_PATH . $tpl;
        $this->smarty->display($this->tpl);
        unset($this->tpl);
        return $this;
    }

}