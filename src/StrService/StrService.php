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

    /**
     * 无线级分类
     * @param $data
     * @param string $pField 父级字段名
     * @param string $cField 子级字段名
     */
    public static function limitLess($param,$pid = 0,$pField='pid',$cField='children'){

        $data = [];
        foreach ($param as $key=>$value) {

            if ($value[$pField] == $pid) {

                $data[] = $value;
                unset($param[$key]);
            }
        }

        if (count($param) != 0) {

            foreach ($data as $k=>&$v) {

                $v[$cField] = self::limitLess($param,$v['id'],$pField,$cField);
            }

        }
        return $data;
    }
}