<?php
namespace hwcvod\vod\service;

use hwcvod\exception\VodException;
use hwcvod\vod\model\CreateDomainAuthInfoReq;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\CreateAuthInfoRsp;
use hwcvod\vod\model\BaseResponse;
use hwcvod\util\DateUtil;
use hwcvod\util\AesCipher;

class DomainUrlAuthService{

    static private $instance;

    static public function getInstance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function createAuthInfoUrl(CreateDomainAuthInfoReq $req, VodClient $vodClient){
        $rsp = new CreateAuthInfoRsp();
        $rsp->setHttpCode(BaseResponse::SUCCESS);

        try {
            $req->build();
            $req->validate();
            $path = $req->getPathFromOriginUrl();
            $data = substr($path,0,strripos($path,'/')+1).'$'.DateUtil::getUtcTime();
            $encryptInfo = AesCipher::encrypt($data, $req->getKey(), true);
            $rsp->setUrl($req->getOriginalUrl().'?auth_info='.urlencode($encryptInfo)."&vhost=".$req->getDomainName());
        }catch(VodException $e) {
            $rsp->setErrorCode('VOD.100021003');
            $rsp->setErrorMsg($e->getMessage());
            $rsp->setHttpCode(BaseResponse::FAIL);
        }
        return $rsp;
    }
}