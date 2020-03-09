<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 16:42
 * QQ: 84855512
 */

namespace lib;

use Symfony\Component\HttpFoundation\JsonResponse;

class response
{
    protected $options = [];
    protected $type = '';
    protected $sended = false;
    /** @var \Symfony\Component\HttpFoundation\Response|JsonResponse $response */
    public $response;
    protected static $self;

    /**
     * @return response
     * @author lejianwen
     */
    public static function _instance($content = '', $status = 200, $headers = [])
    {
        if (!self::$self) {
            self::$self = new self($content, $status, $headers);
        }
        return self::$self;
    }

    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->response = new \Symfony\Component\HttpFoundation\Response($content, $status, $headers);
    }

    /**
     * 重置
     * @return response
     * @author lejianwen
     */
    public static function reset()
    {
        self::$self = null;
        return self::_instance();
    }

    public function setHeader($key, $value, $replace = true)
    {
        $this->response->headers->set($key, $value, $replace);
        return $this;
    }

    public function addHeaders($headers)
    {
        $this->response->headers->add($headers);
        return $this;
    }

    public function getHeader($key, $default = null, $first = true)
    {
        return $this->response->headers->get($key, $default, $first);
    }

    public function allHeader()
    {
        return $this->response->headers->all();
    }

    public function setCallback($callback)
    {
        $this->response->setCallback($callback);
        return $this;
    }

    public function setStatusCode($code, $text = null)
    {
        $this->response->setStatusCode($code, $text);
        return $this;
    }

    public function __call($func, $params)
    {
        return $this->response->$func(...$params);
    }

    /**
     * 设置输出参数
     * @param array $param
     * @param mixed $value
     * @return $this
     */
    public function with($param = [], $value = null)
    {
        if (is_array($param)) {
            $this->options = array_merge($this->options, $param);
        } elseif (is_string($param)) {
            $this->options[$param] = $value;
        }
        return $this;
    }

    public function json($data = [], $status = 200, $headers = [])
    {
        $this->type = 'json';
        $this->with($data);
        $this->addHeaders($headers);
        $this->response = new JsonResponse([], $status, $this->response->headers->all());
        return $this;
    }

    public function jsonp($data = [], $status = 200, $headers = [])
    {
        $this->type = 'jsonp';
        $this->with($data);
        $this->addHeaders($headers);
        $this->response = new JsonResponse([], $status, $this->response->headers->all());
        return $this;
    }

    /**
     * @param $tpl
     * @param int $status
     * @param array $headers
     * @return $this
     */
    public function view($tpl, $status = 200, $headers = [])
    {
        if (!$tpl) {
            throw new \RuntimeException('need template');
        }
        $this->setStatusCode($status);
        $this->addHeaders($headers);
        $this->type = 'view';
        app('view')->setTpl($tpl);
        return $this;
    }

    /**
     * prepareContent
     * @return $this
     * @author Lejianwen
     */
    public function prepareContent()
    {
        $this->response->prepare(request());
        switch ($this->type) {
            case 'json' :
            case 'jsonp' :
                $this->response->setData($this->options);
                break;
            case 'view' :
                /** @var view $view */
                $view = app('view');
                if (!empty($this->options)) {
                    $view->with($this->options);
                }
                $this->response->setContent($view->fetch());
                break;
            default :
                break;
        }
        return $this;
    }

    public function send($force = false)
    {
        if ($force || !$this->sended) {
            $this->prepareContent();
            $this->response->send();
        }
        $this->sended = true;
    }

    /**
     * 保存成静态文件
     * @param $file
     * @return $this
     * @author Lejianwen
     */
    public function saveToHtml($file)
    {
        if (!$this->getContent()) {
            $this->prepareContent();
        }
        $dir = dirname($file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file, $this->getContent());
        return $this;
    }

    /**
     * 跳转
     * @param string $url
     * @param int $status
     */
    public function redirect($url, $status = 302)
    {
        $this->setStatusCode($status)
            ->setHeader('Location', $url);
    }


}
