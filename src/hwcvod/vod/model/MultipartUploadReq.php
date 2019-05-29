<?php
namespace hwcvod\vod\model;

class MultipartUploadReq extends BaseRequest{

    const HTTP_METHOD = 'PUT';

    private $httpVerb = self::HTTP_METHOD;

    private $bucket;

    private $contentMd5;

    private $objectKey;

    private $partNumber;

    private $uploadId;

    /**
     * MultipartUploadReq constructor.
     */
    public function __construct()
    {
        $this->serializedNamedParam['http_verb'] = $this->getHttpVerb();
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
    public function getContentMd5()
    {
        return $this->contentMd5;
    }

    /**
     * @param mixed $contentMd5
     */
    public function setContentMd5($contentMd5)
    {
        $this->contentMd5 = $contentMd5;
        $this->serializedNamedParam['content_md5'] = $contentMd5;
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
    public function getPartNumber()
    {
        return $this->partNumber;
    }

    /**
     * @param mixed $partNumber
     */
    public function setPartNumber($partNumber)
    {
        $this->partNumber = $partNumber;
        $this->serializedNamedParam['part_number'] = $partNumber;
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