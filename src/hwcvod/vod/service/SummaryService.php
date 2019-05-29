<?php
namespace hwcvod\vod\service;
use hwcvod\vod\model\QueryStatReq;
use hwcvod\vod\model\QueryTopStatReq;
use hwcvod\vod\model\QueryDomainReq;
use hwcvod\auth\AuthAkSkRequest;
use hwcvod\vod\client\VodClient;
use hwcvod\exception\VodException;
use hwcvod\generalRequest\HttpResponse;
use hwcvod\generalRequest\CommonFunctions;

class SummaryService{

    static private $instance;

    static public function getInstance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 查询cdn统计
     * @param QueryStatReq $queryStatReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function queryCdnStat(QueryStatReq $queryStatReq, VodClient $vodClient)
    {
        $param = $queryStatReq->reqCdnArray();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/cdn-statistics',VERSION_1_0, $vodClient);
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
     * 查询源站统计信息
     * @param QueryStatReq $queryStatReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function queryVodStat(QueryStatReq $queryStatReq, VodClient $vodClient)
    {
        $param = $queryStatReq->reqVodArray();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/vod-statistics',VERSION_1_0, $vodClient);
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
     * 查询TopN视频信息
     * @param QueryTopStatReq $queryTopStatReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function queryTopStat(QueryTopStatReq $queryTopStatReq, VodClient $vodClient)
    {
        $param = $queryTopStatReq->buildQueryArray();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/top-statistics',VERSION_1_0, $vodClient);
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
     * 查询域名信息
     * @param QueryDomainReq $queryDomainReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function queryDomain(QueryDomainReq $queryDomainReq, VodClient $vodClient)
    {
        $param = $queryDomainReq->queryDomainArray();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/domains',VERSION_1_0, $vodClient);
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