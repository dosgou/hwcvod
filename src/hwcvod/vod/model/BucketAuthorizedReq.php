<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class BucketAuthorizedReq implements JsonSerializable {

    private $projectId;

    private $bucket;

    private $operation;

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
    }

    /**
     * @return mixed
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param mixed $bucket
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}