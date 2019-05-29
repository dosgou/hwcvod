<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class UpdateAssetMetaReq extends BaseRequest implements JsonSerializable {
    /**
     * 租户ID
     */
    private $projectId;

    /**
     * 媒体ID
     */

    private $assetId;

    /**
     * 媒体标题,长度不超过128个字节，utf-8编码
     */
    private $title;

    /**
     * 视频描述, 长度不超过1024个字节
     */
    private $description;

    /**
     * 媒资分类id,此处分类ID类型使用String，是为了更新媒资信息时，更容易判断是否下发category_id
     */
    private $categoryId;

    /**
     * 视频标签，单个标签不超过16个字节，最多不超过16个标签。多个用逗号分隔，UTF8编码
     */
    private $tags;

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
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
        $this->serializedNamedParam['projectId'] = $projectId;
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
        $this->serializedNamedParam['$category_id'] = $categoryId;
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