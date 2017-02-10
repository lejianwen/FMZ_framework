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
 *      UNIQUE KEY `id` (`id`) USING BTREE,
 *      KEY `expire` (`expire`) USING BTREE
 *    )ENGINE=myisam default charset=utf8;
 */
namespace lib\session;
class mysql_pdo extends session
{
    static $dbh = null;
    private $table;
    public function __construct()
    {
        if (self::$dbh === null)
        {
            $this->table = config('app.session_table');
            $this->left_time = config('app.session_lefttime');
            $dns = config('database.driver')
                . ':host=' . config('database.host')
                . ';dbname=' . config('database.database')
                . ';charset=' .config('database.charset');
            self::$dbh = new \PDO($dns, config('database.username'), config('database.password'), array(
               // \PDO::ATTR_PERSISTENT       => TRUE,
                \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => FALSE
            ));
        }
    }

    public function read($session_id)
    {
        $sql = "SELECT `data` FROM {$this->table} WHERE `id` = ? and `expire` > ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array($session_id, date('Y-m-d H:i:s')));
        $data = $stmt->fetch(\PDO::FETCH_ASSOC)['data'];
        return $data;
    }

    public function write($session_id, $data)
    {
        $expire = date('Y-m-d H:i:s', time() + $this->left_time);
        $sql = "INSERT INTO {$this->table} (`id`, `data`, `expire`) values (?, ?, ?) ON DUPLICATE KEY UPDATE data = ?, expire = ?";
        $stmt = self::$dbh->prepare($sql);
        $stmt->execute(array($session_id, $data, $expire, $data, $expire));
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