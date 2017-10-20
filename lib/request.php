<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 14:03
 * QQ: 84855512
 */

namespace lib;

class request
{
    public $ip;
    public $controller;
    public $action;
    public $method;
    public $uri;

    public static function _instance()
    {
        static $self;
        if (!$self) {
            $self = new self();
        }
        return $self;
    }


    public function __construct()
    {
        //$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->setClientIp();
    }

    public function uri()
    {
        return env('REQUEST_URI');
    }

    public function method()
    {
        return env('REQUEST_METHOD');
    }

    public function getMethod()
    {
        return $this->method;
    }

    protected function setAction($action = '')
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    protected function setController($controller = '')
    {
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    /**获取IP地址
     * @return string
     */
    public function getClientIp()
    {
        if (!$this->ip) {
            $this->setClientIp();
        }
        return $this->ip;
    }

    protected function setClientIp()
    {
        if (!$this->ip) {
            if (env('HTTP_CLIENT_IP') && strcasecmp(env('HTTP_CLIENT_IP'), 'unknown')) {
                $ip = env('HTTP_CLIENT_IP');
            } elseif (env('HTTP_X_FORWARDED_FOR') && strcasecmp(env('HTTP_X_FORWARDED_FOR'), 'unknown')) {
                $ip = env('HTTP_X_FORWARDED_FOR');
            } elseif (env('REMOTE_ADDR') && strcasecmp(env('REMOTE_ADDR'), 'unknown')) {
                $ip = env('REMOTE_ADDR');
            } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip = env('HTTP_CLIENT_IP');
            }
            $ip = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
            $this->ip = $ip;
        }
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        $value = isset($_GET[$key]) ? $_GET[$key] : null;
        if ($default !== null && $value === null) {
            $value = $default;
        }
        return $value;
    }

    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        $value = isset($_POST[$key]) ? $_POST[$key] : null;
        if ($default !== null && $value === null) {
            $value = $default;
        }
        return $value;
    }

    public function env($name, $default = null)
    {
        return env($name, $default);
    }

    public function server($name)
    {
        return env($name);
    }
}