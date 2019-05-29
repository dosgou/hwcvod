<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class Parameter extends BaseRequest implements JsonSerializable{

    private $format;

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
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
        $this->serializedNamedParam["format"] = $format;
    }

    public function validate()
    {
        if (empty($this->getFormat())){
            throw new VodException('VOD.100011001',"format is invalidate!");
        }
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