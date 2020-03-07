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

class response extends \Symfony\Component\HttpFoundation\Response
{
    protected $options = [];
    protected $sended = false;
    protected $type = '';
    static $response;

    /**
     * @return response
     * @author lejianwen
     */
    public static function _instance()
    {
        if (!self::$response) {
            self::$response = new self();
        }
        return self::$response;
    }

    /**
     * 重置
     * @return response
     * @author lejianwen
     */
    public static function reset()
    {
        self::$response = null;
        return self::_instance();
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

    public function json($data = [], $status = 200)
    {
        self::$response = new JsonResponse($data, $status);
        return self::$response;
    }

    public function jsonp($data = [], $callback = 'callback', $status = null)
    {
        self::$response = new JsonResponse($data, $status);
        self::$response->setCallback($callback);
        return self::$response;
    }

    /**
     * @param $tpl
     * @param null $status
     * @param string $charset
     * @return $this
     * @throws \Exception
     */
    public function view($tpl, $status = null)
    {
        if (!$tpl) {
            throw new \Exception('need template');
        }
        $this->type = 'view';
        $this->setStatusCode($status);
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
        switch ($this->type) {
            case 'view' :
                /** @var view $view */
                $view = app('view');
                if (!empty($this->options)) {
                    $view->with($this->options);
                }
                $this->setContent($view->fetch());
                break;
            default :
                break;
        }
        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent()
    {
        if ($this->sended) {
            return $this;
        }
        if (!$this->status) {
            $this->setStatus(200);
        }
        if (!$this->content) {
            $this->prepareContent();
        }
        echo $this->content;

        return $this;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
        $this->sended = true;
        return $this;
    }

    /**
     * 保存成静态文件
     * @param $file
     * @return $this
     * @author Lejianwen
     */
    public function saveToHtml($file)
    {
        if (!$this->content) {
            $this->prepareContent();
        }
        $dir = dirname($file);
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file, $this->content);
        return $this;
    }

    /**
     * 跳转
     * @param $url
     * @param string $msg
     * @param int $time
     */
    public function redirect($url, $status = 302)
    {
        $this->setStatus($status)
            ->setHeader('Location', $url);
    }


}
