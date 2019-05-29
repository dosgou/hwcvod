<?php
namespace hwcvod\auth;

use hwcvod\vod\client\VodClient;

class AuthAkSkRequest extends AuthRequest {

    private $requestUrl;

    /**
     * @return string
     */
    public function getRequestUrl(){
        return $this->requestUrl;
    }

    /**
     *  拼接url地址
     */
    public function setRequestUrl(){
        $this->requestUrl = parent::getScheme().'://'.parent::getHost().self::getUri();
    }

    public function sign(VodClient $vodClient){
        $sign = new Signer();
        $sign->AppKey = $vodClient->getVodConfig()->getAk();
        $sign->AppSecret = $vodClient->getVodConfig()->getSk();
        $sign->Sign($this);
        return $sign;
    }

}