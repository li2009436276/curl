<?php


namespace Curl\StrService;

use DateTime;
class DateService
{
    /**
     * 时间间隔
     * @param $startDay
     * @param $endDay
     * @return mixed
     */
    public static function spaceDay($startDay,$endDay){

        $datetimeStart = new DateTime($startDay);
        $datetimeEnd = new DateTime($endDay);
        $days = $datetimeStart->diff($datetimeEnd)->days;
        return $days;
    }
}