<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 14:03
 * QQ: 84855512
 */

namespace lib;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * 继承Symfony的request类
 * Class request
 */
class request extends \Symfony\Component\HttpFoundation\Request
{
    public $file_objs;
    static $self;

    /**
     * The decoded JSON content for the request.
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag|null
     */
    protected $json;

    public static function _instance()
    {
        if (!self::$self) {
            self::$self = self::createFromGlobals();;
        }
        return self::$self;
    }

    /**
     * 重置
     * @author lejianwen
     */
    public static function reset()
    {
        self::$self = null;
    }

    public function input($asResource = false)
    {
        return $this->getContent($asResource);
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query->all();
        } else {
            return parent::get($key, $default);
        }
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->all();
        }

        return $this->request->get($key, $default);
    }

    public function header($name = '', $default = null)
    {
        if ($name == '') {
            return $this->headers->all();
        }
        return $this->headers->get($name, $default);
    }

    /**
     * file
     * @param string $name
     * @return UploadedFile[]|UploadedFile
     * @author Lejianwen
     */
    public function file($name = '')
    {
        if ('' == $name) {
            return $this->files->all();
        } else {
            return $this->files->get($name);
        }
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public function json($key = null, $default = null)
    {
        if (!isset($this->json)) {
            $this->json = new ParameterBag((array)json_decode($this->getContent(), true));
        }

        if (is_null($key)) {
            return $this->json->all();
        }

        return $this->json->all()[$key] ?? $default;
    }

    /**
     * 判断是否是json请求
     * @return bool
     */
    public function isJson()
    {
        $content_type = $this->header('CONTENT_TYPE');
        return $content_type && (mb_strpos($content_type, '/json') !== false || mb_strpos($content_type, '+json') !== false);
    }

    /**
     * @return array|mixed|ParameterBag|null
     */
    public function all()
    {
        if ($this->isJson()) {
            return $this->json();
        }
        return array_merge($this->get(), $this->post());
    }
}
