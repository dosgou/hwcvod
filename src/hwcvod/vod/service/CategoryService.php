<?php
namespace hwcvod\vod\service;

use hwcvod\vod\client\VodClient;
use hwcvod\auth\AuthAkSkRequest;
use hwcvod\vod\model\CreateCategoryReq;
use hwcvod\vod\model\DeleteCategoryReq;
use hwcvod\vod\model\QueryCategoryReq;
use hwcvod\vod\model\EditCategoryReq;
use hwcvod\exception\VodException;
use hwcvod\generalRequest\HttpResponse;
use hwcvod\generalRequest\CommonFunctions;

class CategoryService{

    static private $instance;

    static public function getInstance(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 创建媒资分类
     * @param CreateCategoryReq $createCategoryReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function createAssetCategory(CreateCategoryReq $createCategoryReq, VodClient $vodClient){
        $param = $createCategoryReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/category',VERSION_1_0, $vodClient);
        $authAkSkRequest->setHeaders(array('Content-Type'=>APPLICATION_JSON));
        $authAkSkRequest->setBody(json_encode($param,JSON_UNESCAPED_UNICODE));
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getBody(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }

    /**
     * 查询媒资分类
     * @param QueryCategoryReq $categoryReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function queryAssetCategory(QueryCategoryReq $categoryReq, VodClient $vodClient){
        $categoryReq->validate();
        $param = $categoryReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/category',VERSION_1_0, $vodClient);
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
     * 删除媒资分类
     * @param DeleteCategoryReq $deleteCategoryReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function deleteAssetCategory(DeleteCategoryReq $deleteCategoryReq, VodClient $vodClient){
        $deleteCategoryReq->validate();
        $param = $deleteCategoryReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_DELETE);
        $authAkSkRequest->setUri('/asset/category',VERSION_1_0, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(), $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }

        return null;
    }

    /**
     * 修改媒资分类
     * @param EditCategoryReq $editCategoryReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     */
    public function updateAssetCategory(EditCategoryReq $editCategoryReq, VodClient $vodClient){
        $param = $editCategoryReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_PUT);
        $authAkSkRequest->setUri('/asset/category',VERSION_1_0, $vodClient);
        $authAkSkRequest->setHeaders(array('Content-Type'=>APPLICATION_JSON));
        $authAkSkRequest->setBody(json_encode($param,JSON_UNESCAPED_UNICODE));
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getBody(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }
}