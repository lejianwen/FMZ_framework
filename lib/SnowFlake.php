<?php

namespace lib;

/**
 * 0 | 41 bit | 5bit | 5 bit | 12bit
 * 0或1，1表负数，一般不用  | 时间戳  | 机房id | 机器id | 同一毫秒生成的id序号
 * 可根据情况自行修改bit个数,比如fpm下使用进程id比较合适
 * Class Snow
 * @author Lejianwen
 * @package app\commands
 */
class SnowFlake
{
    const TIMESTAMP_BITS = 41;
    const WORK_ID_BITS = 5; // 机器 5个bit
    const DATA_CENTER_ID_BITS = 5; //机房 5个bit
    const SEQUENCE_BITS = 12; // 12个bit
    const MAX_WORK_ID = -1 ^ (-1 << self::WORK_ID_BITS); // 机器最大id 2的5次方 -1
    const MAX_DATA_CENTER_ID = -1 ^ (-1 << self::DATA_CENTER_ID_BITS); // 机房最大id 2的5次方 -1
    const MAX_SEQUENCE_MASK = -1 ^ (-1 << self::SEQUENCE_BITS); // 最大生成序号 (2的SEQUENCE_BITS次方-1) = 4095;
    const WORK_ID_SHIFT = self::SEQUENCE_BITS; // 机器id偏移
    const DATA_CENTER_ID_SHIFT = self::SEQUENCE_BITS + self::WORK_ID_BITS; //
    const TIMESTAMP_LEFT_SHIFT = self::SEQUENCE_BITS + self::WORK_ID_BITS + self::DATA_CENTER_ID_BITS;
    private static $last_timestamp = 0;
    private static $last_sequence = 0;
    private static $twepoch = 1577808000000; // 2020-01-01

    /**
     * 雪花算法生成的id
     * @param int $data_center_id
     * @param int $worker_id
     * @return int
     * @author Lejianwen
     */
    public static function make($data_center_id = 0, $worker_id = 0)
    {
        if ($data_center_id > self::MAX_DATA_CENTER_ID) {
            throw new  \RangeException('data_center_id max is: ' . self::MAX_DATA_CENTER_ID);
        }
        if ($worker_id > self::MAX_WORK_ID) {
            throw new  \RangeException('worker_id max is: ' . self::MAX_WORK_ID);
        }
        $timestamp = self::timeGen();
        if (self::$last_timestamp == $timestamp) {
            self::$last_sequence = (self::$last_sequence + 1) & self::MAX_SEQUENCE_MASK;
            if (self::$last_sequence == 0) {
                $timestamp = self::tilNextMillis(self::$last_timestamp);
            }
        } else {
            self::$last_sequence = 0;
        }
        self::$last_timestamp = $timestamp;
        return (($timestamp - self::$twepoch) << self::TIMESTAMP_LEFT_SHIFT)
            | ($data_center_id << self::DATA_CENTER_ID_SHIFT)
            | ($worker_id << self::WORK_ID_SHIFT)
            | self::$last_sequence;
    }

    /**
     * 解析雪花算法生成的id
     * @param $snow_flake_id
     * @return \stdClass
     * @author Lejianwen
     */
    public static function unmake($snow_flake_id)
    {
        $binary = str_pad(
            decbin($snow_flake_id),
            (1 + self::TIMESTAMP_BITS + self::DATA_CENTER_ID_BITS + self::WORK_ID_BITS + self::SEQUENCE_BITS),
            '0',
            STR_PAD_LEFT
        );
        $object = new \stdClass();
        $object->timestamp = bindec(substr($binary, 0, self::TIMESTAMP_BITS)) + self::$twepoch;
        $object->data_center_id = bindec(substr($binary, self::TIMESTAMP_BITS + 1, self::DATA_CENTER_ID_BITS));
        $object->worker_id = bindec(
            substr($binary, self::TIMESTAMP_BITS + self::DATA_CENTER_ID_BITS + 1, self::WORK_ID_BITS)
        );
        $object->sequence = bindec(substr($binary, -self::SEQUENCE_BITS));
        return $object;
    }

    private static function tilNextMillis($last_timestamp)
    {
        $timestamp = self::timeGen();
        while ($timestamp <= $last_timestamp) {
            $timestamp = self::timeGen();
        }
        return $timestamp;
    }

    /**
     * @return false|float
     */
    private static function timeGen()
    {
        return floor(microtime(true) * 1000);
    }
}
