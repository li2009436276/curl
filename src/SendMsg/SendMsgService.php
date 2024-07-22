<?php


namespace Curl\SendMsg;

use AlibabaCloud\SDK\Dingtalk\Vrobot_1_0\Dingtalk;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dingtalk\Vrobot_1_0\Models\OrgGroupSendHeaders;
use AlibabaCloud\SDK\Dingtalk\Vrobot_1_0\Models\OrgGroupSendRequest;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

class SendMsgService extends MessageParentService
{

    /**
     * 发送API消息
     * @return void
     */
    public function sendDingtalkApiMsg($content,$msgKey = 'sampleText',$webHookAccessToken = null) {

        $accessToken = $this->getAccessToken();
        if ($accessToken){
           
            $param = [
                "msgParam" => json_encode(['content'=>$content]),
                "msgKey" => $msgKey,
                "token" => $webHookAccessToken ? : "88ef308672e079ae13f6875de97398b350c7cc79ba6234441921e03bcc1330e0"
            ];

            $client = $this->createClient();
            $orgGroupSendHeaders = new OrgGroupSendHeaders([]);
            $orgGroupSendHeaders->xAcsDingtalkAccessToken = $accessToken;
            $orgGroupSendRequest = new OrgGroupSendRequest(
                $param
            );
            try {

                $res = $client->orgGroupSendWithOptions($orgGroupSendRequest, $orgGroupSendHeaders, new RuntimeOptions([]));

            } catch (Exception $err) {
                if (!($err instanceof TeaError)) {
                    $err = new TeaError([], $err->getMessage(), $err->getCode(), $err);
                }
                if (!Utils::empty_($err->code) && !Utils::empty_($err->message)) {
                    // err 中含有 code 和 message 属性，可帮助开发定位问题
                }
            }
        }
        
        
    }

    /**
     * 使用 Token 初始化账号Client
     * @return Dingtalk Client
     */
    protected function createClient(){

        $config = new Config([]);
        $config->protocol = "https";
        $config->regionId = "central";
        return new Dingtalk($config);
    }

}