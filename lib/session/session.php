<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/9
 * Time: 10:41
 * QQ: 84855512
 */
namespace lib\session;
abstract class session
{
    protected $left_time;

    public function open($save_path, $session_name)
    {
        return true;
    }

    public function close()
    {
        $this->gc($this->left_time);
        return true;
    }

    public function read($session_id) { }

    public function write($session_id, $data) { }

    public function destroy($session_id) { }

    public function gc($left_time) { }
}