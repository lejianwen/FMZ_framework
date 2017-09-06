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
        }
        return $self;
    }

    public function get($key = null)
    {
        isset($_SESSION) or session_start();
        if (!$this->session_data) {
            $this->session_data = $_SESSION;
            session_write_close();
        }
        if ($key === null) {
            return $this->session_data;
        }
        return isset($this->session_data[$key]) ? $this->session_data[$key] : null;
    }

    public function set($key, $value)
    {
        if ($key !== null) {
            session_start();
            $_SESSION[$key] = $value;
            $this->session_data[$key] = $value;
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