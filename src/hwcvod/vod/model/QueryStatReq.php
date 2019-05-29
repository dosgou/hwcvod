<?php
namespace hwcvod\vod\model;

class QueryStatReq{

    private $start_time;

    private $end_time;

    private $statType;

    private $interval;

    private $domain;

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
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param mixed $start_time
     */
    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param mixed $end_time
     */
    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    /**
     * @return mixed
     */
    public function getStatType()
    {
        return $this->statType;
    }

    /**
     * @param mixed $statType
     */
    public function setStatType($statType)
    {
        $this->statType = $statType;
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
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function reqCdnArray(){
        $cdnArray = [];
        if(!empty($this->start_time))
        {
            $cdnArray['start_time'] = $this->start_time;
        }

        if(!empty($this->end_time))
        {
            $cdnArray["end_time"] = $this->end_time;
        }

        if(!empty($this->end_time))
        {
            $cdnArray["stat_type"] = $this->statType;
        }
        if(!empty($this->domain))
        {
            $cdnArray["domain"] = $this->domain;
        }
        return $cdnArray;
    }

    public function reqVodArray(){
        $vodArray = [];
        if(!empty($this->start_time))
        {
            $vodArray['start_time'] = $this->start_time;
        }

        if(!empty($this->end_time))
        {
            $vodArray["end_time"] = $this->end_time;
        }
        return $vodArray;
    }
}