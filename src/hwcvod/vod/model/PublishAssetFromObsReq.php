<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class PublishAssetFromObsReq extends BaseRequest implements JsonSerializable {

    private $input;

    private $title;

    private $description;

    private $categoryId;

    private $videoType;

    private $tags;

    private $autoPublish;

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
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @param ObsObjInfo $input
     */
    public function setInput(ObsObjInfo $input)
    {
        $this->input = $input;
        $this->serializedNamedParam['input'] = $input;
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

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->serializedNamedParam as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function validate()
    {
    }
}