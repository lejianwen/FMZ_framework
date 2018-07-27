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
class Store implements \ArrayAccess
{
    //主键，会根据主键查找记录
    protected $pr_key = 'id';
    protected $key;
    protected $model_name;
    protected $model;
    //记录有效时间,没有或者0表示永久
    protected $exp;
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
        if (!$this->key) {
            if ($pr_key) {
                $this->key = $this->model_name . ':' . $pr_key;
            } else {
                $this->key = $this->model_name . ':' . $this->data[$this->pr_key];
            }
        }
        return $this->key;
    }

    /**
     * redis
     * @return redis|\Redis
     * @author Lejianwen
     */
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


    /**
     * model
     * @return \Illuminate\Database\Eloquent\Model
     * @author Lejianwen
     */
    public function model()
    {
        if (!$this->model) {
            $model_name = 'app\\models\\' . ucfirst($this->model_name);
            $this->model = $model_name::find($this->id);
        }
        return $this->model;
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
        $this->expire();
        $this->saved();
        return $this;
    }

    public function saved()
    {

    }

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
            foreach ($data as $key => $value) {
                if (in_array($key, $this->json_attr)) {
                    $data[$key] = json_encode($value);
                }
            }
            static::redis()->hMset($this->key(), $data);
        } else {
            $this->data[$data] = $value;
            static::redis()->hSet($this->key(), $data, $value);
        }
        $this->expire();
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
        if (is_float($step)) {
            $this->data[$column] = static::redis()->hIncrByFloat($this->key(), $column, $step);
        } else {
            $this->data[$column] = static::redis()->hIncrBy($this->key(), $column, $step);
        }

        $this->expire();
        $this->updated();
    }

    public function decrement($column, $step = 1)
    {
        if (!isset($this->data[$column])) {
            return;
        }
        $step = -abs($step);
        if (is_float($step)) {
            $this->data[$column] = static::redis()->hIncrByFloat($this->key(), $column, $step);
        } else {
            $this->data[$column] = static::redis()->hIncrBy($this->key(), $column, $step);
        }
        $this->expire();
        $this->updated();
    }

    public function __toString()
    {
        return json_encode($this->data);
    }

    public function toArray()
    {
        return $this->data;
    }


    public function offsetExists($key)
    {
        return !is_null($this->getAttribute($key));
    }

    public function offsetUnset($key)
    {
        unset($this->data[$key], $this->data[$key]);
    }

    public function offsetSet($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function offsetGet($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * 添加延时
     * @param int $time
     * @author Lejianwen
     */
    public function expire($time = 0)
    {
        $time = $time ?: $this->exp;
        if ($time == 0) {
            return;
        }
        $key = $this->key();
        static::redis()->expire($key, $time);
    }
}