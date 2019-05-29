<?php
namespace hwcvod\vod\model;

class CompleteMultipartUploadReq extends BaseRequest {

    const HTTP_METHOD = 'POST';

    private $httpVerb = self::HTTP_METHOD;

    private $bucket;

    private $objectKey;

    private $uploadId;

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
     * MultipartUploadReq constructor.
     */
    public function __construct()
    {
        $this->serializedNamedParam['http_verb'] = $this->getHttpVerb();
    }


    /**
     * @return mixed
     */
    public function getHttpVerb()
    {
        return $this->httpVerb;
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
        $this->serializedNamedParam['bucket'] = $bucket;
    }

    /**
     * @return mixed
     */
    public function getObjectKey()
    {
        return $this->objectKey;
    }

    /**
     * @param mixed $objectKey
     */
    public function setObjectKey($objectKey)
    {
        $this->objectKey = $objectKey;
        $this->serializedNamedParam['object_key'] = $objectKey;
    }

    /**
     * @return mixed
     */
    public function getUploadId()
    {
        return $this->uploadId;
    }

    /**
     * @param mixed $uploadId
     */
    public function setUploadId($uploadId)
    {
        $this->uploadId = $uploadId;
        $this->serializedNamedParam['upload_id'] = $uploadId;
    }

    public function validate()
    {
    }
}