<?php
namespace hwcvod\vod\model;

class CreateAuthInfoReq extends BaseRequest {
    private $userIp;

    private $url;

    private $assetId;

    private $key;

    private $checkLevel = 5;

    private static $checkLevelList = array(1,2,3,5);

    private static $needToCheckIpCheckLevelList = array(1,2);

    public function __set($key,$value){
        $this->$key = $value;
    }

    public function __get($value){
        if(isset($this->$value)){
            return $this->$value;
        }else{
            return null;
        }

    }

    /**
     * @return mixed
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @param mixed $userIp
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getAssetId()
    {
        return $this->assetId;
    }

    /**
     * @param mixed $assetId
     */
    public function setAssetId($assetId)
    {
        $this->assetId = $assetId;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function getCheckLevel()
    {
        return $this->checkLevel;
    }

    /**
     * @param int $checkLevel
     */
    public function setCheckLevel($checkLevel)
    {
        $this->checkLevel = $checkLevel;
    }

    public function validate()
    {
    }
}