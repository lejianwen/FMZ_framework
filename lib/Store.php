<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/9
 * Time: 9:08
 */

namespace lib;

/**
 * Class Store
 * 基于redis的模型，方便使用
 * @package lib
 */
class Store
{
    //主键，会根据主键查找记录
    protected $pr_key = 'id';
    protected $key;
    protected $model_name;
    //记录有效时间
    protected $exp = 3600;
    //属性数组
    protected $data;
    //需要转换成json的属性
    protected $json_attr = [];
    //源数据
    protected $origin_data;
    //
    protected static $redis_connect = 'default';

    public function __construct()
    {
        if (!$this->model_name) {
            $path = explode('\\', static::class);
            $model_name = strtolower(array_pop($path));
            $this->model_name = $model_name;
        }
    }

    protected function key($pr_key = null)
    {
        if ($pr_key) {
            $this->key = $this->model_name . ':' . $pr_key;
        } else {
            $this->key = $this->model_name . ':' . $this->data[$this->pr_key];
        }
        return $this->key;
    }

    protected static function redis()
    {
        return redis(static::$redis_connect);
    }

    /**
     * 根据主键查找信息
     * @param $id
     * @return null|static
     * @author Lejianwen
     */
    public static function find($id)
    {
        $store = new static();
        $key = $store->key($id);
        $data = static::redis()->hGetAll($key);
        if (!$data) {
            return null;
        }
        $store->origin_data = $data;
        $store->data();
        return $store;
    }

    protected function data()
    {
        $this->data = $this->origin_data;
        $this->deCodeJsonAttr();
    }

    protected function originData()
    {
        $this->origin_data = $this->data;
        $this->enCodeJsonAttr();
    }

    public static function init($data)
    {
        if (empty($data)) {
            return null;
        }
        $store = new static();
        if (empty($data[$store->pr_key])) {
            return null;
        }
        $store->data = $data;
        return $store->save();
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    public function getAttribute($name)
    {
        if (!$name) {
            return null;
        }
        if (empty($this->data)) {
            return null;
        }
        if (!isset($this->data[$name])) {
            return null;
        }
        return $this->data[$name];
    }

    public function setAttribute($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function save()
    {
        $this->originData();
        $data = $this->origin_data;
        $key = $this->key();
        static::redis()->hMset($key, $data);
        if ($this->exp) {
            static::redis()->expire($key, $this->exp);
        }
        $this->saved();
        return $this;
    }

    /**
     * save之后会调用
     * @author Lejianwen
     */
    public function saved()
    {

    }

    /**
     * update之后会调用
     * @author Lejianwen
     */
    public function updated()
    {

    }

    /**
     * 将子属性的数组转成json
     * @author Lejianwen
     */
    protected function enCodeJsonAttr()
    {
        if (!empty($this->json_attr)) {
            foreach ($this->json_attr as $attr) {
                if (isset($this->data[$attr])) {
                    $this->origin_data[$attr] = json_encode($this->data[$attr]);
                }
            }
        }
    }

    /**
     * 将子属性的json转成数组
     * @author Lejianwen
     */
    protected function deCodeJsonAttr()
    {
        if (!empty($this->json_attr)) {
            foreach ($this->json_attr as $attr) {
                if (isset($this->origin_data[$attr])) {
                    $this->data[$attr] = json_decode($this->origin_data[$attr], true);
                }
            }
        }
    }

    /**
     * 更新
     * @param $data
     * @param null $value
     * @author Lejianwen
     */
    public function update($data, $value = null)
    {
        if (empty($data)) {
            return;
        }
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
            $this->save();
        } else {
            $this->data[$data] = $value;
            $this->save();
        }
        $this->updated();
    }

    public function delete()
    {
        $key = $this->key();
        static::redis()->del($key);
    }

    public function increment($column, $step = 1)
    {
        if (!isset($this->data[$column])) {
            return;
        }
        $this->data[$column] = static::redis()->hIncrBy($this->key(), $column, $step);
        $this->updated();
    }

    public function decrement($column, $step = 1)
    {
        if (!isset($this->data[$column])) {
            return;
        }
        $step = -abs($step);
        $this->data[$column] = static::redis()->hIncrBy($this->key(), $column, $step);
        $this->updated();
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

}