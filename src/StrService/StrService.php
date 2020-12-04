<?php


namespace Curl\StrService;


class StrService
{
    /**
     * 随机生成6位字符串，可能重复
     * @param int $length
     */
    public static function randStr($length = 6){

        $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        return substr(str_shuffle($strs),mt_rand(0,strlen($strs)-$length-1),$length);
    }
}