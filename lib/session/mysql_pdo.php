<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/7
 * Time: 17:36
 * QQ: 84855512
 */
/**
 *CREATE TABLE session (
 *      id varchar(255) NOT NULL,
 *      expire TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 *      data blob,
 *      UNIQUE KEY `session_id` (`session_id`)
 *    )ENGINE=myisam default charset=utf8;
 */
namespace lib\session;
class mysql_pdo
{
    const DNS = "mysql:host=127.0.0.1;dbname=test;charset=utf8";
    const USER = 'root';
    const PASS = '';
    const LEFT_TIME = 600;
    static $dbh = null;
    private $table = 'session';
    public function __construct()
    {
        if (self::$dbh === null)
        {
            self::$dbh = new \PDO(self::DNS, self::USER, self::PASS, array(
               // \PDO::ATTR_PERSISTENT       => TRUE,
                \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => FALSE
            ));
        }
    }

    public function open($savePath, $sessionName)
    {
        return true;
    }

    public function close()
    {
        $this->gc(self::LEFT_TIME);
        return true;
    }

    public function write($session_id, $data)
    {
        $expire = date('Y-m-d H:i:s', time() + self::LEFT_TIME);
        $sql = "INSERT INTO {$this->table} (`id`, `data`, `expire`) values (?, ?, ?) ON DUPLICATE KEY UPDATE data = ?, expire = ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array($session_id, $data, $expire, $data, $expire));
    }

    public function read($session_id)
    {
        $sql = "SELECT `data` FROM {$this->table} WHERE `id` = ? and `expire` > ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array($session_id, date('Y-m-d H:i:s')));
        $data = $stmt->fetch(\PDO::FETCH_ASSOC)['data'];
        return $data;
    }

    public function destroy($session_id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array($session_id));
        $dbh = NULL;
        return TRUE;
    }

    public function gc($left_time)
    {
        $sql = "DELETE FROM {$this->table} WHERE expire < ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array(date('Y-m-d H:i:s')));
        $dbh = NULL;
        return TRUE;
    }

}