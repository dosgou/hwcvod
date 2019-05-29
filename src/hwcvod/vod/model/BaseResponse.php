<?php
namespace hwcvod\vod\model;

class BaseResponse{

    /** status的两种状态 */
    const SUCCESS = 0;

    const FAIL = 1;

    protected  $serializedNamedParam = array();

    /**
     * @return array
     */
    public function getSerializedNamedParam()
    {
        return $this->serializedNamedParam;
    }

    /**
     * @param array $serializedNamedParam
     */
    public function setSerializedNamedParam(array $serializedNamedParam)
    {
        $this->serializedNamedParam = $serializedNamedParam;
    }

    /** 任务状态,根据转码服务的响应设置，假如请求成功，设置为0；请求失败，设置为1 */
    private $status;

    /**
     * http状态码
     */
    private $httpCode;


    /** 公共响应头中的X-request-id */
    private $xRequestId;

    /** 异常响应 */
    private $errorCode;

    private $errorMsg;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param mixed $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return mixed
     */
    public function getXRequestId()
    {
        return $this->xRequestId;
    }

    /**
     * @param mixed $xRequestId
     */
    public function setXRequestId($xRequestId)
    {
        $this->xRequestId = $xRequestId;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param mixed $errorMsg
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
    }
}