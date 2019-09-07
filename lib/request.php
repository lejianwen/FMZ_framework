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
    public $server;
    public $header;
    public $file_objs;
    static $self;

    public static function _instance()
    {
        if (!self::$self) {
            self::$self = new self();
        }
        return self::$self;
    }

    /**
     * 重置
     * @return request
     * @author lejianwen
     */
    public static function reset()
    {
        self::$self = null;
        return self::_instance();
    }

    public function __construct()
    {
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
            } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],
                    'unknown')) {
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

    public function server($name = '')
    {
        if (empty($this->server)) {
            $this->server = $_SERVER;
        }
        if ('' === $name) {
            return $this->server;
        }
        return $this->server[$name];
    }

    public function header($name = '', $default = null)
    {
        if (empty($this->header)) {
            $header = [];
            if (function_exists('apache_request_headers') && $result = apache_request_headers()) {
                $header = $result;
            } else {
                $server = $this->server ?: $_SERVER;
                foreach ($server as $key => $val) {
                    if (0 === strpos($key, 'HTTP_')) {
                        $key = str_replace('_', '-', strtolower(substr($key, 5)));
                        $header[$key] = $val;
                    }
                }
                if (isset($server['CONTENT_TYPE'])) {
                    $header['content-type'] = $server['CONTENT_TYPE'];
                }
                if (isset($server['CONTENT_LENGTH'])) {
                    $header['content-length'] = $server['CONTENT_LENGTH'];
                }
            }
            $this->header = array_change_key_case($header);
        }
        if (is_array($name)) {
            return $this->header = array_merge($this->header, $name);
        }
        if ('' === $name) {
            return $this->header;
        }
        $name = str_replace('_', '-', strtolower($name));
        return isset($this->header[$name]) ? $this->header[$name] : $default;
    }

    /**
     * file
     * @param string $name
     * @return file[]||file
     * @author Lejianwen
     */
    public function file($name = '')
    {
        if (empty($this->file_objs)) {
            $files = isset($_FILES) ? $_FILES : [];
            $file_objs = [];
            if (IS_CLI) {
                // swoole 模式下
                foreach ($files as $key => $file) {
                    $file_objs[$key] = $this->arrayFileKeyInCli($file);
                }

            } else {
                // 普通模式下
                foreach ($files as $key => $file) {
                    if (isset($file['name']) && is_array($file['name'])) {
                        //多文件上传
                        $file_objs[$key] = $this->arrayFileKey($file['tmp_name'], $file['name'], $file['type']);
                    } else {
                        // 单文件
                        if (empty($file['tmp_name']) || !is_file($file['tmp_name'])) {
                            continue;
                        }
                        $file_objs[$key] = (new file($file['tmp_name']))->setUpInfo([
                            'name' => $file['name'],
                            'type' => $file['type']
                        ]);
                    }
                }
            }
            $this->file_objs = $file_objs;
        }
        if ('' == $name) {
            return $this->file_objs;
        } else {
            return $this->file_objs[$name];
        }
    }

    protected function arrayFileKeyInCli($tmp_file, $re = [])
    {
        foreach ($tmp_file as $k => $v) {
            if (is_array($v)) {
                $re[$k] = $this->arrayFileKeyInCli($v, $re);
            } else {
                $re = $tmp_file;
                $re['file'] = (new file($tmp_file['tmp_name']))->setUpInfo([
                    'name' => $tmp_file['name'],
                    'type' => $tmp_file['type'],
                ]);
                break;
            }
        }
        return $re;
    }

    protected function arrayFileKey($tmp_name, $name, $type, $re = [])
    {
        foreach ($tmp_name as $k => $v) {
            if (is_array($v)) {
                $re[$k] = $this->arrayFileKey($v, $name[$k], $type[$k], $re);
            } else {
                $re[$k] = (new file($v))->setUpInfo([
                    'name' => $name[$k],
                    'type' => $type[$k]
                ]);
            }
        }
        return $re;
    }
}