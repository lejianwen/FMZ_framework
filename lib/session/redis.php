<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/9
 * Time: 10:40
 * QQ: 84855512
 */
namespace lib\session;
class redis extends session
{
    static $client;

    public function __construct()
    {
        if (!self::$client)
        {
            $this->left_time = config('app.session_lefttime');
            self::$client = new \Redis();
            self::$client->connect(config('redis.host'), config('redis.port'));
            if ($password = config('redis.password'))
                self::$client->auth($password);
            if ($db = config('app.session_redis_db'))
                self::$client->select($db);
        }

    }

    public function read($session_id)
    {
        return self::$client->get($session_id) ?: '';
    }

    public function write($session_id, $data)
    {
        self::$client->setex($session_id, $this->left_time, $data);
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