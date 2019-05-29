<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class Review extends BaseRequest implements JsonSerializable {
    //设置时间间隔
    private $interval;
    //审核政治
    private $politics;
    //审核暴恐
    private $terrorism;
    //审核色情
    private $porn;

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
     * Review constructor.
     */
    public function __construct()
    {
        $this->jsonSerialize();
    }


    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param mixed $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
        $this->serializedNamedParam['interval'] = $interval;
    }

    /**
     * @return mixed
     */
    public function getPolitics()
    {
        return $this->politics;
    }

    /**
     * @param mixed $politics
     */
    public function setPolitics($politics)
    {
        $this->politics = $politics;
        $this->serializedNamedParam['politics'] = $politics;
    }

    /**
     * @return mixed
     */
    public function getTerrorism()
    {
        return $this->terrorism;
    }

    /**
     * @param mixed $terrorism
     */
    public function setTerrorism($terrorism)
    {
        $this->terrorism = $terrorism;
        $this->serializedNamedParam['terrorism'] = $terrorism;
    }

    /**
     * @return mixed
     */
    public function getPorn()
    {
        return $this->porn;
    }

    /**
     * @param mixed $porn
     */
    public function setPorn($porn)
    {
        $this->porn = $porn;
        $this->serializedNamedParam['porn'] = $porn;
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