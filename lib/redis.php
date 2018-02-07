<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/7
 * Time: 10:24
 */

namespace lib;

class redis
{
    /**
     * @var \Redis $client
     */
    protected $client;
    protected $config;

    public static function _instance($name = 'default')
    {
        static $store;
        if (!isset($store[$name]) || !$store[$name]) {
            $store[$name] = new static($name);
        }
        return $store[$name];
    }

    public function __construct($name)
    {
        $this->config = config("redis.{$name}");
    }

    public function connect()
    {
        if (!$this->client) {
            $config = $this->config;
            $this->client = new \Redis();
            $this->client->connect($config['host'], $config['port']);
            if (!empty($config['pwd'])) {
                $this->client->auth($config['pwd']);
            }
            if (!empty($config['db'])) {
                $this->client->select($config['db']);
            }
            if (!empty($config['prefix'])) {
                $this->client->setOption(\Redis::OPT_PREFIX, $config['prefix']);
            }
        }
        return $this;
    }

    public function disConnect()
    {
        if ($this->client) {
            $this->client = null;
        }
    }

    public static function __callStatic($func, $params)
    {
        return static::_instance()->$func(...$params);
    }

    public function __call($func, $params)
    {
        return $this->connect()->client->$func(...$params);
    }
}