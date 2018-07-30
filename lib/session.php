<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/8
 * Time: 9:44
 * QQ: 84855512
 */

namespace lib;

class session
{
    protected $session_data;

    public static function _instance()
    {
        static $self;
        if (!$self) {
            $self = new self();
            if (!isset($_SESSION)) {
                if (config('app.session_dir')) {
                    session_save_path(config('app.session_dir'));
                }
                session_start();
                $self->session_data = $_SESSION;
                session_write_close();
            }
        }
        return $self;
    }

    public function get($key = null)
    {
        if ($key === null) {
            return $this->session_data;
        }
        return isset($this->session_data[$key]) ? $this->session_data[$key] : null;
    }

    /**
     * set
     * 设置session
     * @param string|array $key
     * @param $value
     * @author Lejianwen
     */
    public function set($key, $value = '')
    {
        if ($key !== null) {
            session_start();
            if (is_array($key)) {
                $this->session_data = array_merge($this->session_data, $key);
            } else {
                $this->session_data[$key] = $value;
            }
            $_SESSION = $this->session_data;
            session_write_close();
        }
    }

    public function del($key)
    {
        session_start();
        unset($_SESSION[$key]);
        unset($this->session_data[$key]);
        session_write_close();
    }
}