<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/13
 * Time: 11:26
 * QQ: 84855512
 */
namespace lib\cache;

use lib\cache;

class predis extends cache
{
    protected $expire;
    static $client;

    public function __construct()
    {
        if (!self::$client)
        {
            $this->expire = config('app.cache_expire');
            self::$client = new \Predis\Client(config('redis'));
            if ($db = config('app.cache_redis_db'))
                self::$client->select($db);
        }
        $this->gc();
    }

    public function set($key, $value, $expire = null)
    {
        if ($expire === null)
            $expire = $this->expire;
        $key = $this->getCacheKey($key);
        $value = serialize($value);
        if (self::$client->setex($key, $expire, $value))
            return true;
        return false;
    }

    public function isExists($key)
    {
        $key = $this->getCacheKey($key);
        if (self::$client->keys($key))
            return true;
        return false;
    }

    protected function getCacheKey($key)
    {
        return md5('cache' . $key);
    }

    public function get($key)
    {
        $key = $this->getCacheKey($key);
        return self::$client->get($key) ? unserialize(self::$client->get($key)) : '';
    }

    /**回收缓存
     * @param $expire_only boolean 是否只清除已过期的
     */
    protected function gc($expire_only = true)
    {
        if (!$expire_only)
            self::$client->flushDB();
    }
}