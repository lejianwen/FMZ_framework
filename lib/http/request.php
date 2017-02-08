<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 14:03
 * QQ: 84855512
 */
namespace lib\http;
class request
{
    public $ip;
    public $controller;
    public $action;
    public $method;
    public $url;

    public static function _instance()
    {
        static $self;
        if(!$self)
        {
            $self = new self();
        }
        return $self;
    }
    
    
    public function __construct()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $arr_uri = explode('/', $uri);
        $this->setController($arr_uri[1] ?: 'index');
        $this->setAction($arr_uri[2] ?: 'index');
        $this->setMethod($_SERVER['REQUEST_METHOD']);
        $this->setUrl($_SERVER['REDIRECT_URL']);
        $this->setClientIp();
    }

    protected function setUrl($url = '')
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    protected function setMethod($method = '')
    {
        $this->method = $method;
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
        if(!$this->ip)
        {
            $this->setClientIp();
        }
        return $this->ip;
    }

    protected function setClientIp()
    {
        if(!$this->ip)
        {
            if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
            {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
            {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
            {
                $ip = getenv('REMOTE_ADDR');
            } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else
            {
                $ip = getenv('HTTP_CLIENT_IP');
            }
            $ip = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches [0] : '';
            $this->ip = $ip;
        }
    }
}