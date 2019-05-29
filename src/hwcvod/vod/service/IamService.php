<?php
namespace hwcvod\vod\service;

use hwcvod\vod\client\VodClient;
use hwcvod\generalRequest\CommonFunctions;
use hwcvod\vod\model\AuthObj;
use hwcvod\exception\VodException;
use hwcvod\vod\model\CredentialReq;

class IamService
{
    static private $instance;

    static public function getInstance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //统一身份认证接口地址
    //const GET_TOKEN_URL='https://192.145.63.209:31943/v3/auth/tokens';

    //const GET_SECURITY_TOKEN_URL='https://192.145.63.209:31943/v3.0/OS-CREDENTIAL/securitytokens';

    //统一身份认证接口地址
    const GET_TOKEN_URL = 'https://iam.cn-north-1.myhuaweicloud.com/v3/auth/tokens';

    const GET_SECURITY_TOKEN_URL = 'https://iam.cn-north-1.myhuaweicloud.com/v3.0/OS-CREDENTIAL/securitytokens';

    /**
     * @param $name
     * @param $password
     * @param $domainName
     * @param $duration
     * @param VodClient $vodClient
     * @return null
     */
    public function requestTemporaryCredential($name, $password, $domainName, $duration, VodClient $vodClient){
        $getTokenReq = new AuthObj($name, $password, $domainName, $vodClient);
        $tokenBody = json_encode($getTokenReq,JSON_UNESCAPED_UNICODE);
        $tokenHeader = ['Content-Type'=>APPLICATION_JSON];
        $token="";

        try {
            $getTokenRsp = CommonFunctions::http(self::GET_TOKEN_URL, $tokenBody, HTTP_METHOD_POST, $tokenHeader);
            $headerData = explode(PHP_EOL,$getTokenRsp->getHeader());
            //从响应头中获取token
            foreach ($headerData as $key => $val){
                if ($val !== null && strstr($val,"X-Subject-Token")) $token = explode(": ",$val)[1];
            }

            //将获取的token装填到请求临时AKSK请求中
            $securityTokenHeader = ['Content-Type'=>APPLICATION_JSON,'X-Auth-Token'=>$token];
            $getSecurityTokenReq = new CredentialReq($token,$duration);
            $securityTokenBody = json_encode($getSecurityTokenReq,JSON_UNESCAPED_UNICODE);
            $getSecurityTokenRsp = CommonFunctions::http(self::GET_SECURITY_TOKEN_URL, $securityTokenBody, HTTP_METHOD_POST, $securityTokenHeader);
            //输出结果
            return $getSecurityTokenRsp->getBody();
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }
}