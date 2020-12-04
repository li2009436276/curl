<?php


namespace Curl\CurlService;


class CurlService
{
    static $curl = null;


    public static function getInstance(){

        if (!self::$curl) {

            self::$curl = curl_init();
        }
    }

    static function __callStatic($name,$param){

        self::getInstance();
        curl_setopt(self::$curl, CURLOPT_URL, $param[0]);
        if(substr($param[0],0,5) == 'https'){
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$curl, CURLOPT_ENCODING, 'gzip');
        $param[2] = !empty($param[2]) ? $param[2] : ['Content-type:application/json;','Accept:application/json'];
        curl_setopt(self::$curl,CURLOPT_HTTPHEADER,$param[2]);

        switch (strtolower($name)) {
            case 'get': return self::getReq();
            case 'post': return self::postReq(!empty($param[1]) ? $param[1] : []);
        }

    }

    /**
     * get提交
     * @return mixed
     */
    static function getReq(){

       return self::finish();
    }

    /**
     * post提交
     * @param $data
     * @return mixed
     */
    static function postReq($data = []){

        curl_setopt(self::$curl, CURLOPT_POSTFIELDS, json_encode($data));
        return self::finish();
    }

    /**
     * 处理并消除curl
     * @return mixed
     */
    static function finish(){

        $output = curl_exec(self::$curl);
        $output = json_decode($output,true);

        return $output;
    }

    /**
     * 关闭curl
     */
    static function close(){
        curl_close(self::$curl);
    }
}