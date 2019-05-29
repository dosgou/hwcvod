<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class SubtitleReq extends BaseRequest implements JsonSerializable{

    private $id = 0;

    private $type = null;

    private $language = null;

    private $md5 = null;

    private $description = null;

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
     * SubtitleReq constructor.
     */
    public function __construct()
    {
        $this->jsonSerialize();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->serializedNamedParam['id'] = $id;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->serializedNamedParam['type'] = $type;
    }

    /**
     * @return null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param null $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        $this->serializedNamedParam['language'] = $language;
    }

    /**
     * @return null
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * @param null $md5
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        $this->serializedNamedParam['md5'] = $md5;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->serializedNamedParam['description'] = $description;
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