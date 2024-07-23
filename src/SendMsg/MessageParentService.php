<?php

namespace Curl\SendMsg;

use AlibabaCloud\SDK\Dingtalk\Voauth2_1_0\Dingtalk;
use AlibabaCloud\SDK\Dingtalk\Voauth2_1_0\Models\GetAccessTokenRequest;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;
use Darabonba\OpenApi\Models\Config;
use Illuminate\Support\Facades\Cache;

class MessageParentService
{

    public function sendTextMsg($content,$url){


        $msg = [
            'msgtype' => 'text',
            'text' => [
                "content" => $content
            ],
            'at' => [
                /*'atMobiles' => [
                    "156xxxx8827",
                    "189xxxx6325"
                ],*/
                'isAtAll' => true

            ]
        ];


        $this->sendUrl($url,json_encode($msg));
    }

    /**
     * 发送markdown消息
     * @param $title
     * @param $message
     * @param $url
     * @return void
     */
    public function sendMarkdownMsg($title,$message,$url){


        $msg = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title'=> $title,
                "text" => $message
            ],
            'at' => [
                /*'atMobiles' => [
                    "156xxxx8827",
                    "189xxxx9325"
                ],*/
                'isAtAll' => true

            ]
        ];


        $this->sendUrl($url,json_encode($msg));
    }

    protected function getAccessToken(){

        //

        $accessToken = Cache::get("dingTalk:application:access:token");
        if (!$accessToken) {

            $client = $this->createClient();
            $getAccessTokenRequest = new GetAccessTokenRequest([
                "appKey" => env("dingtalk_appKey"),
                "appSecret" => env("dingtalk_appSecret")
            ]);
            try {

                $res = $client->getAccessToken($getAccessTokenRequest);
                if ($res && !empty($res->body)) {

                    Cache::put('dingTalk:application:access:token',$res->body->accessToken,7200);
                    return $res->body->accessToken;
                }
            }catch (\Exception $err) {
                if (!($err instanceof TeaError)) {
                    $err = new TeaError([], $err->getMessage(), $err->getCode(), $err);
                }
                if (!Utils::empty_($err->code) && !Utils::empty_($err->message)) {
                    // err 中含有 code 和 message 属性，可帮助开发定位问题
                }
            }


        }
        return $accessToken;
    }

    /**
     * 使用 Token 初始化账号Client
     * @return Dingtalk Client
     */
    private function createClient(){
        $config = new Config([]);
        $config->protocol = "https";
        $config->regionId = "central";
        return new Dingtalk($config);
    }

    private function sendUrl($remote_server, $post_string)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 不用开启curl证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        //$info = curl_getinfo($ch);
        //var_dump($info);
        curl_close($ch);
        return $data;
    }
    
}