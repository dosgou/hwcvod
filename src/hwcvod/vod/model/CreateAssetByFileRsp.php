<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class CreateAssetByFileRsp extends BaseResponse implements JsonSerializable {

    private $assetId;

    private $videoUploadUrl;

    private $coverUploadUrl;

    private $subtitleUploadUrls;

    private $target;

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
    public function getVideoUploadUrl()
    {
        return $this->videoUploadUrl;
    }

    /**
     * @param mixed $videoUploadUrl
     */
    public function setVideoUploadUrl($videoUploadUrl)
    {
        $this->videoUploadUrl = $videoUploadUrl;
        $this->serializedNamedParam['video_upload_url'] = $videoUploadUrl;
    }

    /**
     * @return mixed
     */
    public function getCoverUploadUrl()
    {
        return $this->coverUploadUrl;
    }

    /**
     * @param mixed $coverUploadUrl
     */
    public function setCoverUploadUrl($coverUploadUrl)
    {
        $this->coverUploadUrl = $coverUploadUrl;
        $this->serializedNamedParam['cover_upload_url'] = $coverUploadUrl;
    }

    /**
     * @return mixed
     */
    public function getSubtitleUploadUrls()
    {
        return $this->subtitleUploadUrls;
    }

    /**
     * @param mixed $subtitleUploadUrls
     */
    public function setSubtitleUploadUrls($subtitleUploadUrls)
    {
        $this->subtitleUploadUrls = $subtitleUploadUrls;
        $this->serializedNamedParam['subtitle_upload_urls'] = $subtitleUploadUrls;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
        $this->serializedNamedParam['target'] = $target;
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

class Target implements JsonSerializable {

    private $bucket;

    private $location;

    private $object;

    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param mixed $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}