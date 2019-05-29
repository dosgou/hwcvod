<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class UpdateAssetReq extends BaseRequest implements JsonSerializable {

    private static $videoTypes = array('MP4','TS','MOV','MXF','MPG','FLV');

    private static $coverTypes = array('JPG','PNG');

    private $assetId;

    private $videoMd5;

    private $videoName;

    private $videoType;

    private $coverId;

    private $coverMd5;

    private $coverType;

    private $subtitles;

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
    public function getVideoMd5()
    {
        return $this->videoMd5;
    }

    /**
     * @param mixed $videoMd5
     */
    public function setVideoMd5($videoMd5)
    {
        $this->videoMd5 = $videoMd5;
        $this->serializedNamedParam['video_md5'] = $videoMd5;
    }

    /**
     * @return mixed
     */
    public function getVideoName()
    {
        return $this->videoName;
    }

    /**
     * @param mixed $videoName
     */
    public function setVideoName($videoName)
    {
        $this->videoName = $videoName;
        $this->serializedNamedParam['video_name'] = $videoName;
    }

    /**
     * @return mixed
     */
    public function getVideoType()
    {
        return $this->videoType;
    }

    /**
     * @param mixed $videoType
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;
        $this->serializedNamedParam['video_type'] = $videoType;
    }

    /**
     * @return mixed
     */
    public function getCoverId()
    {
        return $this->coverId;
    }

    /**
     * @param mixed $coverId
     */
    public function setCoverId($coverId)
    {
        $this->coverId = $coverId;
        $this->serializedNamedParam['cover_id'] = $coverId;
    }

    /**
     * @return mixed
     */
    public function getCoverMd5()
    {
        return $this->coverMd5;
    }

    /**
     * @param mixed $coverMd5
     */
    public function setCoverMd5($coverMd5)
    {
        $this->coverMd5 = $coverMd5;
        $this->serializedNamedParam['cover_md5'] = $coverMd5;
    }

    /**
     * @return mixed
     */
    public function getCoverType()
    {
        return $this->coverType;
    }

    /**
     * @param mixed $coverType
     */
    public function setCoverType($coverType)
    {
        $this->coverType = $coverType;
        $this->serializedNamedParam['cover_type'] = $coverType;
    }

    /**
     * @return mixed
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * @param mixed $subtitles
     */
    public function setSubtitles($subtitles)
    {
        $this->subtitles = $subtitles;
        $this->serializedNamedParam['subtitles'] = $subtitles;
    }



    public function validate()
    {
    }


    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->serializedNamedParam as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}