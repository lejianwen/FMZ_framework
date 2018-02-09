<?php
/**
 * Created by PhpStorm.
 * User: Lejianwen
 * Date: 2018/2/9
 * Time: 9:08
 */

namespace lib;

class Store
{
    //主键，会根据主键查找记录
    protected $pr_key = 'id';
    protected $model_name;
    //记录有效时间
    protected $exp = 3600;
    //属性数组
    protected $data;
    //需要转换成json的属性
    protected $json_attr = [];
    //源数据
    protected $origin_data;

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
            return $this->model_name . ':' . $pr_key;
        }
        return $this->model_name . ':' . $this->data[$this->pr_key];

    }

    public static function info($id)
    {
        $store = new static();
        $key = $store->key($id);
        $data = redis()->hGetAll($key);
        if (!$data) {
            return null;
        }
        $store->origin_data = $data;
        $store->data = $data;
        $store->deCodeJsonAttr();
        return $store;
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
        $this->origin_data = $this->data;
        $this->enCodeJsonAttr();
        $data = $this->origin_data;
        $key = $this->key();
        redis()->hMset($key, $data);
        if ($this->exp) {
            redis()->expire($key, $this->exp);
        }
        return $this;
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
    }

    public function delete()
    {
        $key = $this->key();
        redis()->del($key);
    }
}