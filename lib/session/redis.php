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
    protected $client;

    public function __construct()
    {
        if (!$this->client) {
            $this->left_time = config('app.session_lefttime');
            $name = !empty(config('app.session_redis_dir')) ? config('app.session_redis_dir') : 'default';
            $this->client = \lib\redis::_instance($name);
        }
    }

    public function read($session_id)
    {
        return $this->client->get($session_id) ?: '';
    }

    public function write($session_id, $data)
    {
        $this->client->setex($session_id, $this->left_time, $data);
    }

    public function destroy($session_id)
    {
        $this->client->del($session_id);
    }

}