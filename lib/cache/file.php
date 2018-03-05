<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/13
 * Time: 11:26
 * QQ: 84855512
 */

namespace lib\cache;

use lib\cache;

class file extends cache
{
    protected $file_dir;
    protected $expire;

    public function __construct()
    {
        $this->file_dir = config('app.cache_file_dir');
        if (!is_dir($this->file_dir)) {
            @mkdir($this->file_dir, 0777, true);
        }
        $this->expire = config('app.cache_expire');
//        $this->gc();
    }

    public function set($key, $value, $expire = null)
    {
        if ($expire === null) {
            $expire = $this->expire;
        }
        $expire += time();
        $value = serialize($value);
//        if (function_exists('gzcompress')) {
//            $value = gzcompress($value);
//        }
        $file = $this->getCacheFile($key);
        if (@file_put_contents($file, $value, LOCK_EX) == strlen($value)) {
            @chmod($file, 0777);
            return @touch($file, $expire);
        }
        return false;
    }

    public function isExists($key)
    {
        $cacheFile = $this->getCacheFile($key);
        if (@filemtime($cacheFile) && @filemtime($cacheFile) > time()) {
            return true;
        }
        return false;

    }

    protected function getCacheFile($key)
    {
        return $this->file_dir . '/' . md5($key) . '.tmp';
    }

    public function get($key)
    {
        $cacheFile = $this->getCacheFile($key);
        if (($time = @filemtime($cacheFile)) > time()) {
            $value = file_get_contents($cacheFile);
//            if (function_exists('gzcompress')) {
//                $value = gzuncompress($value);
//            }
            return unserialize($value);
        } else {
            if ($time) {
                @unlink($cacheFile);
            }
        }
        return false;
    }

    /**回收缓存
     * @param $expire_only boolean 是否只清除已过期的
     */
    public function gc($expire_only = true)
    {
        if (($handle = opendir($this->file_dir)) === false) {
            return;
        }
        while (($file = readdir($handle)) !== false) {
            if ($file[0] === '.' || $file[0] === '..') {
                continue;
            } else {
                if (@filemtime($this->file_dir . $file) < time() || !$expire_only) {
                    @unlink($this->file_dir . $file);
                }
            }
        }
        closedir($handle);
    }

    public function del($key)
    {
        $cacheFile = $this->getCacheFile($key);
        if (is_file($cacheFile)) {
            @unlink($cacheFile);
        }
        return false;
    }
}