<?php
namespace hwcvod\vod\service;

use hwcvod\vod\model\CreateAuthInfoReq;
use hwcvod\vod\model\CreateAuthInfoRsp;

class UrlAuthService{

    const GUARD_ENC_TYPE = 2;

    const UES_GUARD_ENC_TYPE = true;

    /**
     * 域名防盗链接口
     * @param CreateAuthInfoReq $req
     * @return CreateAuthInfoRsp
     */
    public static function createDomainAuthInfoUrl(CreateAuthInfoReq $req){
        $currentUtcTime = DateUtil::getUtcTime();
        $createAuthInfoRsp = new CreateAuthInfoRsp();
        try{
            //参数校验
            $req->validate();

            //获取未加密串
            $unEncryptInfo = self::getUnencryptInfo($req->getUserIp(), $currentUtcTime, $req->getAssetId(), $req->getCheckLevel());

            //进行加密
            $encryptInfo = AesCipher::encrypt($unEncryptInfo, $req->getKey(), false);

            if(empty($encryptInfo)){
                $createAuthInfoRsp->setErrorCode('VOD.100021003');
                $createAuthInfoRsp->setErrorMsg('encrypt url failed encrypt fail');
                $createAuthInfoRsp->setStatus(BaseResponse::FAIL);
                return $createAuthInfoRsp;
            }

            //加密信息进行url编码
            $encodeInfo = self::encode($encryptInfo, $currentUtcTime);

            //获取带有鉴权信息的URL
            $createAuthInfoRsp->setUrl(self::getAuthInfoUrl($req->getUrl(), $encodeInfo));
            $createAuthInfoRsp->setStatus(BaseResponse::SUCCESS);

            return $createAuthInfoRsp;

        } catch(VodException $e){
            $createAuthInfoRsp->setErrorCode('VOD.100011001');
            $createAuthInfoRsp->setErrorMsg('Request parameters is invalid parameter is illegal');
            $createAuthInfoRsp->setStatus(BaseResponse::FAIL);
            return $createAuthInfoRsp;

        } catch(Exception $e){
            $createAuthInfoRsp->setErrorCode('VOD.100021003');
            $createAuthInfoRsp->setErrorMsg('encrypt fail');
            $createAuthInfoRsp->setStatus(BaseResponse::FAIL);
            return $createAuthInfoRsp;
        }
    }


    private static function getUnencryptInfo($ip, $time, $assetId, $checkLevel)
    {
        return $ip.'$'.$time.'$'.$assetId.'$'.$checkLevel;
    }

    private static function encode($toEncodeInfo, $time)
    {
        return urlencode($toEncodeInfo.':'.$time.':UTC');
    }

    private static function getAuthInfoUrl($url, $authInfo)
    {
        $authInfoUrl = $url.'?auth_info='.$authInfo;
        if (self::UES_GUARD_ENC_TYPE){
            return $authInfoUrl.'&GuardEncType='.self::GUARD_ENC_TYPE;
        }
        return $authInfoUrl;
    }
}