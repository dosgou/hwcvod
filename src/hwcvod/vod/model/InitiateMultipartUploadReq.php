<?php
namespace hwcvod\vod\model;

class InitiateMultipartUploadReq extends BaseRequest{

    const HTTP_METHOD = 'POST';

    private $httpVerb = self::HTTP_METHOD;

    private $bucket;

    private $contentType;

    private $objectKey;

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
     * InitiateMultipartUploadReq constructor.
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
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param mixed $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->serializedNamedParam['content_type'] = $contentType;
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

    public function validate()
    {
        if (empty($this->bucket)){
            throw new VodException('VOD.100011001',"bucket is invalidate!");
        }

        if(empty($this->objectKey))
        {
            throw new VodException('VOD.100011001',"object_key is invalidate!");
        }

        if(empty($this->contentType))
        {
            throw new VodException('VOD.100011001',"content_type is invalidate!");
        }
    }
}