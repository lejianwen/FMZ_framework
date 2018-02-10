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
        $name = $name ?: 'default';
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
            if (!empty($config['database'])) {
                $this->client->select($config['database']);
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

    /**
     * 给有序集合添加元素，并判断是否已经超过限制
     * 超过限制则根据是否倒序剔除多余的元素
     * @param $column
     * @param $score
     * @param $id
     * @param int $limit
     * @param bool $desc
     * @author Lejianwen
     */
    public function toAttrList($column, $score, $id, $limit = 1000, $desc = true)
    {
        $key = "{$column}:list";
        redis()->zAdd($key, $score, $id);
        $num = redis()->zCard($key);
        if ($num > $limit) {
            if ($desc) {
                //倒序删除多出来分数小的
                $this->connect()->client->zRemRangeByRank($key, 0, ($num - $limit - 1));
            } else {
                $this->connect()->client->zRemRangeByRank($key, ($limit - $num), -1);
            }
        }
    }

    /**
     * 根据某字段的排行榜，是个存在redis中的有序集合
     * @param string $column 字段
     * @param int $offset 偏移数
     * @param int $limit 返回行数
     * @param bool $desc 是否倒序
     * @return array
     * @author Lejianwen
     */
    public function attrList($column, $offset = 0, $limit = 5, $desc = true)
    {
        if ($limit <= 0) {
            return [];
        }
        $offset_end = $limit - 1;
        if ($desc) {
            $list = $this->connect()->client->zRevRange("{$column}:list", $offset, $offset_end);
        } else {
            $list = $this->connect()->client->zRange("{$column}:list", $offset, $offset_end);
        }
        return $list;
    }
}