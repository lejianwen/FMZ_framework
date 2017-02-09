<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/9
 * Time: 10:40
 * QQ: 84855512
 */
namespace lib\session;
class predis implements session
{
    static $client;

    public function __construct()
    {
        if (!self::$client)
        {
            self::$client = new \Predis\Client(config('redis'));
            if ($db = config('app.session_redis_db'))
                self::$client->select($db);
        }

    }

    public function open($save_path, $session_name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($session_id)
    {
        return self::$client->get($session_id) ?: '';
    }

    public function write($session_id, $data)
    {
        self::$client->setex($session_id, config('app.session_lefttime'), $data);
    }

    public function destroy($session_id)
    {
        self::$client->del($session_id);
    }

    public function gc($left_time)
    {
        return true;
    }
}