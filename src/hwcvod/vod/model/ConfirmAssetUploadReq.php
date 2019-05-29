<?php
namespace hwcvod\vod\model;

class ConfirmAssetUploadReq extends BaseRequest{

    private $assetId;

    private $status;

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

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->serializedNamedParam['status'] = $status;
    }

    public function validate()
    {
    }
}