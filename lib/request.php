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

/**
 * 改成返回\Symfony\Component\HttpFoundation\Request
 * Class request
 * @package lib
 */
class request extends \Symfony\Component\HttpFoundation\Request
{
    public $file_objs;
    static $self;

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
}
