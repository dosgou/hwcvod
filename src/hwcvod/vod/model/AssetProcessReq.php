<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class AssetProcessReq extends BaseRequest implements JsonSerializable {

    private $assetId;

    private $transPresetId;

    private $watermarkTemplateId;

    private $templateGroupName;

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
    public function getTransPresetId()
    {
        return $this->transPresetId;
    }

    /**
     * @param mixed $transPresetId
     */
    public function setTransPresetId($transPresetId)
    {
        $this->transPresetId = $transPresetId;
        $this->serializedNamedParam['trans_template_id'] = $transPresetId;
    }

    /**
     * @return mixed
     */
    public function getWatermarkTemplateId()
    {
        return $this->watermarkTemplateId;
    }

    /**
     * @param mixed $watermarkTemplateId
     */
    public function setWatermarkTemplateId($watermarkTemplateId)
    {
        $this->watermarkTemplateId = $watermarkTemplateId;
        $this->serializedNamedParam['watermark_template_id'] = $watermarkTemplateId;
    }

    /**
     * @return mixed
     */
    public function getTemplateGroupName()
    {
        return $this->templateGroupName;
    }

    /**
     * @param mixed $templateGroupName
     */
    public function setTemplateGroupName($templateGroupName)
    {
        $this->templateGroupName = $templateGroupName;
        $this->serializedNamedParam['template_group_name'] = $templateGroupName;
    }


    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->getSerializedNamedParam() as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function validate()
    {
    }
}