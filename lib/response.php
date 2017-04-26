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
    protected $status = 200;
    protected $header = [];
    protected $options = [];
    protected $type = '';
    protected $jsonp_callback = 'callback';
    protected $sended = false;

    public static function _instance()
    {
        static $self;
        if (!$self)
        {
            $self = new self();
        }
        return $self;
    }

    public function __construct()
    {
        $this->setContentType('text/html');
    }

    /**设置状态
     * @param $code
     * @return $this
     * @deprecated 被系统的http_response_code替代
     */
    /*public function status($code)
    {
        $_status = array(
            // Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  // 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            // 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if (isset($_status[$code]))
        {
            header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
            // 确保FastCGI模式下正常
            header('Status:' . $code . ' ' . $_status[$code]);
        }
        return $this;
    }*/

    /**设置状态
     * @param $status
     * @return $this
     */
    public function status($status = 200)
    {
        $this->status = $status;
        return $this;
    }

    /**设置contentType
     * @param $content_type
     * @param string $charset
     */
    public function setContentType($content_type, $charset = 'utf-8')
    {
        $this->setHeader('Content-Type', $content_type . '; charset=' . $charset);
    }

    /**设置头信息
     * @param $name
     * @param $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        if (is_array($name))
        {
            $this->header = array_merge($this->header, $name);
        } else
        {
            $this->header[$name] = $value;
        }
        return $this;
    }

    /**发送头信息
     *
     */
    public function header()
    {
        if (!headers_sent())
        {
            http_response_code($this->status);
            foreach ($this->header as $key => $value)
            {
                header($key . ':' . $value);
            }
        }
    }


    /**设置输出参数
     * @param array $options
     * @return $this
     */
    public function with($options = [])
    {
        if (is_array($options))
            $this->options = array_merge($this->options, $options);
        elseif (is_string($options))
            $this->options = $options;
        return $this;
    }

    public function json($data = [], $status = null)
    {
        $this->type = 'json';
        $status === null or $this->status = $status;
        $this->setContentType('application/json');
        $this->with($data);
    }

    public function jsonp($data = [], $callback = 'callback', $status = null)
    {
        $this->type = 'jsonp';
        $status === null or $this->status = $status;
        $this->setContentType('application/json');
        $this->with($data);
        $this->jsonp_callback = $callback;
    }

    public function view($tpl, $status = null)
    {
        if (!$tpl)
            throw new \Exception('need template');
        $this->type = 'view';
        $status === null or $this->status = $status;
        $this->setContentType('text/html');
        $view = app('view');
        if (!empty($this->options))
        {
            if (is_array($this->options))
            {
                foreach ($this->options as $key => $val)
                {
                    $view->with($key, $val);
                }
            }

        }
        $view->setTpl($tpl);
        return $this;
    }

    /**响应
     *
     */
    public function send()
    {
        if ($this->sended)
            return;
        $this->header();
        switch ($this->type)
        {
            case 'json' :
                echo json_encode($this->options);
                break;
            case 'jsonp' :
                echo($this->jsonp_callback . '(' . json_encode($this->options) . ');');
                break;
            case 'view' :
                app('view')->display();
                break;
            default :
                break;
        }
        if (function_exists('fastcgi_finish_request'))
        {
            // FASTCGI下提高页面响应
            fastcgi_finish_request();
        }
        $this->sended = true;
    }

    /**跳转
     * @param $url
     * @param string $msg
     * @param int $time
     */
    public function redirect($url, $msg = '', $time = 0)
    {
        $this->status = 301;
        $this->header();
        if (empty($msg))
            $msg = "redirect to  {$url} after {$time} s!";
        if (!headers_sent())
        {
            // redirect
            if (0 === $time)
            {
                header('Location: ' . $url);
            } else
            {
                header("refresh:{$time};url={$url}");
                echo $msg;
            }
        } else
        {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if ($time != 0)
                $str .= $msg;
            echo $str;
        }
        $this->sended = true;
    }
}