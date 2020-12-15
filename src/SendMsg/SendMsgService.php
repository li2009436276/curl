<?php


namespace Curl\SendMsg;


class SendMsgService
{
    public function sendDDMsg($content){


        $msg = [
            'msgtype' => 'text',
            'text' => [
                "content" => $content
            ],
            'at' => [
                /*'atMobiles' => [
                    "156xxxx8827",
                    "189xxxx8325"
                ],*/
                'isAtAll' => true

            ]
        ];

        $dd = 'https://oapi.dingtalk.com/robot/send?access_token=b7ba86418cd10fe93b2c64fcf472876835ebc8ce155f28d353423dd776126710';
        $this->sendUrl($dd,json_encode($msg));
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