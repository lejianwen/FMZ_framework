<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/13
 * Time: 11:22
 * QQ: 84855512
 */

namespace lib;

abstract class cache
{
    protected $expire;

    public static function _instance()
    {
        static $self;
        if ($self === null) {
            $class = 'lib\cache\\' . config('app.cache');
            $self = new $class;
        }

        return $self;
    }

    public function set($key, $value, $expire = null)
    {

    }

    public function isExists($key)
    {

    }

    public function get($key)
    {

    }

    public function clear()
    {
        $this->gc(false);
    }

    /**回收缓存
     * @param $expire_only boolean 是否只清除已过期的
     */
    protected function gc($expire_only = true)
    {

    }
}