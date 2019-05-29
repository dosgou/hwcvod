<?php
namespace hwcvod\vod\model;

class CreateAssetByFileReq extends AssetFileData {

    private $title;

    private $description;

    private $categoryId;

    private $videoSize;

    private $tags;

    private $autoPublish;

    private $templateGroupName;

    private $watermarkTemplateId;

    private $thumbPresetId;

    private $autoPreheat;

    private $autoEncrypt;

    private $thumbnail;

    private $review;

    private $subtitles = array();

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->serializedNamedParam['title'] = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->serializedNamedParam['description'] = $description;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->serializedNamedParam['category_id'] = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getVideoSize()
    {
        return $this->videoSize;
    }

    /**
     * @param mixed $videoSize
     */
    public function setVideoSize($videoSize)
    {
        $this->videoSize = $videoSize;
        $this->serializedNamedParam['videoSize'] = $videoSize;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        $this->serializedNamedParam['tags'] = $tags;
    }

    /**
     * @return mixed
     */
    public function getAutoPublish()
    {
        return $this->autoPublish;
    }

    /**
     * @param mixed $autoPublish
     */
    public function setAutoPublish($autoPublish)
    {
        $this->autoPublish = $autoPublish;
        $this->serializedNamedParam['auto_publish'] = $autoPublish;
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
    public function getThumbPresetId()
    {
        return $this->thumbPresetId;
    }

    /**
     * @param mixed $thumbPresetId
     */
    public function setThumbPresetId($thumbPresetId)
    {
        $this->thumbPresetId = $thumbPresetId;
        $this->serializedNamedParam['thumb_template_id'] = $thumbPresetId;
    }

    /**
     * @return mixed
     */
    public function getAutoPreheat()
    {
        return $this->autoPreheat;
    }

    /**
     * @param mixed $autoPreheat
     */
    public function setAutoPreheat($autoPreheat)
    {
        $this->autoPreheat = $autoPreheat;
        $this->serializedNamedParam['auto_preheat'] = $autoPreheat;
    }

    /**
     * @return mixed
     */
    public function getAutoEncrypt()
    {
        return $this->autoEncrypt;
    }

    /**
     * @param mixed $autoEncrypt
     */
    public function setAutoEncrypt($autoEncrypt)
    {
        $this->autoEncrypt = $autoEncrypt;
        $this->serializedNamedParam['auto_encrypt'] = $autoEncrypt;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        $this->serializedNamedParam['thumbnail'] = $thumbnail;
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

    /**
     * @return array
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * @param array $subtitles
     */
    public function setSubtitles(array $subtitles)
    {
        $this->subtitles = $subtitles;
        $this->serializedNamedParam['subtitles'] = $subtitles;
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        foreach ($this->serializedNamedParam as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function validate() {
    }
}