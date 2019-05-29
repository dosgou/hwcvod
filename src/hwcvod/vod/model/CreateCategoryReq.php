<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class CreateCategoryReq extends BaseRequest implements JsonSerializable
{
    //视频分类名称，最大64字节
    private $name;
    //父分类ID，若不填，则默认生成一级分类，根节点分类ID为0
    private $parentId;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->serializedNamedParam['name'] = $name;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        $this->serializedNamedParam['parent_id'] = $parentId;
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