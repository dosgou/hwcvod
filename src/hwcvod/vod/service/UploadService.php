<?php
namespace hwcvod\vod\service;

use hwcvod\vod\model\InitiateMultipartUploadReq;
use hwcvod\vod\model\MultipartUploadReq;
use hwcvod\vod\model\CompleteMultipartUploadReq;
use hwcvod\vod\model\ListPartsReq;
use hwcvod\auth\AuthAkSkRequest;
use hwcvod\generalRequest\CommonFunctions;
use hwcvod\exception\VodException;
use hwcvod\vod\client\VodClient;
use hwcvod\generalRequest\HttpResponse;

class UploadService{

    static private $instance;

    static public function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    const HTTP_URI = '/asset/authority';

    /**
     * 获取初始化分段上传签名字符串
     * @param InitiateMultipartUploadReq $initiateMultipartUploadReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function initiateMultipartUpload(InitiateMultipartUploadReq $initiateMultipartUploadReq, VodClient $vodClient)
    {
        $initiateMultipartUploadReq->validate();
        $param = $initiateMultipartUploadReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri(self::HTTP_URI,VERSION_1_1, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }

    /**
     * 获取分段上传签名字符串
     * @param MultipartUploadReq $multipartUploadReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function multipartUpload(MultipartUploadReq $multipartUploadReq, VodClient $vodClient)
    {
        $multipartUploadReq->validate();
        $param = $multipartUploadReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri(self::HTTP_URI,VERSION_1_1, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }

    /**
     * 获取列举分段上传已上传段签名字符串
     * @param ListPartsReq $listPartsReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function listParts(ListPartsReq $listPartsReq, VodClient $vodClient)
    {
        $listPartsReq->validate();
        $param = $listPartsReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri(self::HTTP_URI,VERSION_1_1, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }

    /**
     * 获取分段上传合并段签名字符串
     * @param CompleteMultipartUploadReq $completeMultipartUploadReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function completeMultipartUpload(CompleteMultipartUploadReq $completeMultipartUploadReq, VodClient $vodClient)
    {
        $completeMultipartUploadReq->validate();
        $param = $completeMultipartUploadReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri(self::HTTP_URI,VERSION_1_1, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }
}