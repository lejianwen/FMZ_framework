<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 16:42
 * QQ: 84855512
 */

namespace lib;

class response
{
    protected $status;
    protected $content;
    protected $headers = [];
    protected $options = [];
    protected $type = '';
    protected $jsonp_callback = 'callback';
    protected $sended = false;
    public static $self;

    /**
     * @return response
     * @author lejianwen
     */
    public static function _instance()
    {
        if (!self::$self) {
            self::$self = new self();
        }
        return self::$self;
    }

    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->headers = $headers;
        $this->setContent($content);
        $this->setStatus($status);
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

    /**
     * setContent
     * @param $content
     * @return $this
     * @author Lejianwen
     */
    public function setContent($content)
    {
        if (null !== $content && !is_string($content) && !is_numeric($content) && !is_callable(array(
                $content,
                '__toString'
            ))) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.',
                gettype($content)));
        }

        $this->content = (string)$content;

        return $this;
    }

    /**
     * getContent
     * @return mixed
     * @author Lejianwen
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 设置状态
     * @param $status
     * @return $this
     */
    public function setStatus($status = 200)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**设置contentType
     * @param $content_type
     * @param string $charset
     */
    public function setContentType($content_type, $charset = 'utf-8')
    {
        $this->setHeader('Content-Type', $content_type . '; charset=' . $charset);
    }

    /**
     * 设置头信息
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        if (is_array($name)) {
            $this->headers = array_merge($this->headers, $name);
        } else {
            $this->headers[$name] = $value;
        }
        return $this;
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

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOptions($name = null)
    {
        if (!$name) {
            return $this->options;
        } elseif (!isset($this->options[$name])) {
            return null;
        } else {
            return $this->options[$name];
        }
    }

    public function json($data = [], $status = 200)
    {
        $this->type = 'json';
        if ($status) {
            $this->setStatus($status);
        }
        $this->setContentType('application/json');
        $this->with($data);
        return $this;
    }

    public function jsonp($data = [], $callback = 'callback', $status = null)
    {
        $this->type = 'jsonp';
        if ($status) {
            $this->setStatus($status);
        }
        $this->setContentType('application/json');
        $this->with($data);
        $this->jsonp_callback = $callback;
        return $this;
    }

    /**
     * @param        $tpl
     * @param null $status
     * @param string $content_type
     * @param string $charset
     * @return $this
     * @throws \Exception
     */
    public function view($tpl, $status = null, $content_type = 'text/html', $charset = 'utf-8')
    {
        if (!$tpl) {
            throw new \Exception('need template');
        }
        $this->type = 'view';
        if ($status) {
            $this->setStatus($status);
        }
        $this->setContentType($content_type, $charset);
        app('view')->setTpl($tpl);
        return $this;
    }

    /**
     * Sends HTTP headers.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $key => $value) {
                header($key . ':' . $value);
            }
        }

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
            case 'json' :
                $this->setContent(json_encode($this->options));
                break;
            case 'jsonp' :
                $this->setContent($this->jsonp_callback . '(' . json_encode($this->options) . ');');
                break;
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
    public function redirect($url, $msg = '', $time = 0, $status = 302)
    {
        $this->status = $status;
        $this->sendHeaders();
        if (empty($msg)) {
            $msg = "redirect to  {$url} after {$time} s!";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                echo $msg;
            }
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0) {
                $str .= $msg;
            }
            echo $str;
        }
        $this->sended = true;
    }

    /**
     * Cleans or flushes output buffers up to target level.
     *
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     *
     * @param int $targetLevel The target output buffering level
     * @param bool $flush Whether to flush or clean the buffers
     *
     * @final since version 3.3
     */
    public static function closeOutputBuffers($targetLevel, $flush)
    {
        $status = ob_get_status(true);
        $level = count($status);
        // PHP_OUTPUT_HANDLER_* are not defined on HHVM 3.3
        $flags = defined('PHP_OUTPUT_HANDLER_REMOVABLE') ? PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE) : -1;

        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || ($s['flags'] & $flags) === $flags : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }

}