<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class PublishAssetReq extends BaseRequest implements JsonSerializable{

    private $assetId;

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
        $this->serializedNamedParam['asset_id'] = $assetId;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function validate()
    {
    }
}