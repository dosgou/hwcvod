<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class Thumbnail extends BaseRequest implements JsonSerializable {

    private $type = null;

    private $percent;

    private $time;

    private $dots;

    private $coverPosition;

    private $format;

    private $aspectRatio;

    private $maxLength;

    /**
     * Thumbnail constructor.
     */
    public function __construct()
    {
        $this->jsonSerialize();
    }

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
     * @return mixed
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        $this->serializedNamedParam['percent'] = $percent;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
        $this->serializedNamedParam['time'] = $time;
    }

    /**
     * @return mixed
     */
    public function getDots()
    {
        return $this->dots;
    }

    /**
     * @param mixed $dots
     */
    public function setDots($dots)
    {
        $this->dots = $dots;
        $this->serializedNamedParam['dots'] = $dots;
    }

    /**
     * @return mixed
     */
    public function getCoverPosition()
    {
        return $this->coverPosition;
    }

    /**
     * @param mixed $coverPosition
     */
    public function setCoverPosition($coverPosition)
    {
        $this->coverPosition = $coverPosition;
        $this->serializedNamedParam['cover_position'] = $coverPosition;
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
        $this->serializedNamedParam['format'] = $format;
    }

    /**
     * @return mixed
     */
    public function getAspectRatio()
    {
        return $this->aspectRatio;
    }

    /**
     * @param mixed $aspectRatio
     */
    public function setAspectRatio($aspectRatio)
    {
        $this->aspectRatio = $aspectRatio;
        $this->serializedNamedParam['aspect_ratio'] = $aspectRatio;
    }

    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @param mixed $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        $this->serializedNamedParam['max_length'] = $maxLength;
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