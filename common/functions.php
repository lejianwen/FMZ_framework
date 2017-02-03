<?php
/**
 * Created by PhpStorm.
 * User: lejianwen
 * Date: 2017/2/3
 * Time: 11:52
 * QQ: 84855512
 */
namespace common;
class functions
{
    protected static function rad($d)
    {
        return $d * 3.1415926535898 / 180.0;
    }

    /**根据经纬度计算距离
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float|int
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $EARTH_RADIUS = 6378.137;
        $radLat1 = self::rad($lat1);
        //echo $radLat1;
        $radLat2 = self::rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::rad($lng1) - self::rad($lng2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) +
                cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * $EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }

    /**中文拼音首字母
     * @param $s0
     * @return null|string
     */
    public static function getFirstChar($s0)
    {
        $fchar = ord($s0{0});
        if ($fchar >= ord("A") and $fchar <= ord("z")) return strtoupper($s0{0});
        $s1 = iconv("UTF-8", "gb2312", $s0);
        $s2 = iconv("gb2312", "UTF-8", $s1);
        if ($s2 == $s0)
        {
            $s = $s1;
        } else
        {
            $s = $s0;
        }
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17923) return "G";
        if ($asc >= -17922 and $asc <= -17418) return "I";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }

    /**中文字符串的拼音首字母
     * @param $zh
     * @return string
     */
    public static function getPinYinFirstChar($zh)
    {
        $ret = "";
        $s1 = iconv("UTF-8", "gb2312", $zh);
        $s2 = iconv("gb2312", "UTF-8", $s1);
        if ($s2 == $zh)
        {
            $zh = $s1;
        }
        for ($i = 0; $i < strlen($zh); $i++)
        {
            $s1 = substr($zh, $i, 1);
            $p = ord($s1);
            if ($p > 160)
            {
                $s2 = substr($zh, $i++, 2);
                $ret .= self::getFirstChar($s2);
            } else
            {
                $ret .= $s1;
            }
        }
        return $ret;
    }


    /**多进程执行任务
     * 阻塞的情况下其实并不是多进程，只是创建了一个子进程
     * 非阻塞才会创建多个子进程
     * @param int $num
     * @param $callback
     */
    public function doByFork($num = 10, $callback)
    {
        $pid = [];
        for ($i=0;$i<$num;$i++)
        {
            $pid[$i] = pcntl_fork(); //创建子进程
            if ( $pid[$i] == 0) {//子进程
                if ($callback instanceof \Closure)
                    $callback($i);
                exit;//必须退出，不然会递归多进程
            } else {//主进程
                //pcntl_wait($status,WNOHANG); //不等待子进程结束，非阻塞，可不写
                //$pid[$i] = pcntl_wait($status, WUNTRACED); //等待子进程结束 ，阻塞
                // pcntl_waitpid($pid[$i], $status);
            }
        }
        //统一等待结束，防止僵尸进程
        foreach ($pid as $i => $_pid) {
            if($_pid) {
                pcntl_waitpid($_pid, $status);
            }
        }
    }
}
