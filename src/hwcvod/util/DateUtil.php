<?php
namespace hwcvod\util;

class DateUtil{
    const DATE_TIME_PATTERN = 'YmdHis';

    /**
     * 获取UTC时间
     * @return string
     */
    public static function getUtcTime(){
        date_default_timezone_set('UTC');
        $timestamp = new \DateTime();
        $timeStr = $timestamp->format(self::DATE_TIME_PATTERN);
        return $timeStr;
    }
}
