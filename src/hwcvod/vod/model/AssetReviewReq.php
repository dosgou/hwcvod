<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class AssetReviewReq extends BaseRequest implements JsonSerializable{

    private $assetId;

    private $review;

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
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
        $this->serializedNamedParam['review'] = $review;
    }

    public function validate()
    {
    }


    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        foreach ($this->serializedNamedParam as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}