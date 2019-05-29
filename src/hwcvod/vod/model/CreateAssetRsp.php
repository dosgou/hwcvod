<?php
namespace hwcvod\vod\model;

class CreateAssetRsp extends BaseRequest
{
    private $assetId;

    private $coverUploadUrl;

    private $videoUploadUrl;

    private $subtitleUploadUrls;

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

    public function validate()
    {
    }
}