<?php
namespace hwcvod\vod\service;

use Exception;
use hwcvod\vod\model\CreateAssetByFileReq;
use hwcvod\vod\model\QueryAssetMetaReq;
use hwcvod\vod\model\QueryAssetDetailReq;
use hwcvod\vod\model\PreheatingAssetReq;
use hwcvod\vod\model\QueryAssetListReq;
use hwcvod\vod\model\QueryAssetCiphersReq;
use hwcvod\vod\model\DeleteAssetReq;
use hwcvod\vod\model\UpdateAssetMetaReq;
use hwcvod\vod\model\AssetProcessReq;
use hwcvod\vod\model\ConfirmAssetUploadReq;
use hwcvod\vod\model\UpdateAssetReq;
use hwcvod\vod\model\PublishAssetFromObsReq;
use hwcvod\vod\model\PublishAssetReq;
use hwcvod\vod\model\ExtractAudioTaskReq;
use hwcvod\vod\model\AssetReviewReq;
use hwcvod\vod\model\BucketAuthorizedReq;
use hwcvod\vod\model\CreateSmartCoverReq;
use hwcvod\vod\client\VodClient;
use hwcvod\auth\AuthAkSkRequest;
use hwcvod\exception\VodException;
use hwcvod\generalRequest\CommonFunctions;
use hwcvod\generalRequest\HttpResponse;

class AssetService{

    static private $instance;

    static public function getInstance(){
      if (!self::$instance instanceof self) {
              self::$instance = new self();
        }
         return self::$instance;
    }

    /**
     * 只创建不上传，可选择其他方式上传
     * @param CreateAssetByFileReq $createAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function createAssetByFile(CreateAssetByFileReq $createAssetReq, VodClient $vodClient){
        $param = $createAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset',VERSION_1_0, $vodClient);
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
     * 创建并上传媒资带OBS分段上传回调
     * @param CreateAssetByFileReq $createAssetReq
     * @param VodClient $vodClient
     * @return null
     * @throws VodException
     */
    public function createAssetByFileAuto(CreateAssetByFileReq $createAssetReq, VodClient $vodClient){
        $param = $createAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset',VERSION_1_0, $vodClient);
        $authAkSkRequest->setHeaders(array('Content-Type'=>APPLICATION_JSON));
        $authAkSkRequest->setBody(json_encode($param,JSON_UNESCAPED_UNICODE));
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getBody(),
                $authAkSkRequest->getMethod(), $authAkSkRequest->getHeaders());
            if (!empty($response->getBody())){
                $vodRsp = json_decode($response->getBody(),false);
                if (property_exists($vodRsp,VIDEO_UPLOAD_URL)){
                    $vodUploadUrl = $vodRsp->{VIDEO_UPLOAD_URL};
                    $coverUploadUrl = $vodRsp->{COVER_UPLOAD_URL};
                    $subtitleUploadUrl = $vodRsp->{SUBTITLE_UPLOAD_URL};
                    $bucket = $vodRsp->{'target'}->{'bucket'};
                    $objectKey = $vodRsp->{'target'}->{'object'};
                    if (!empty($vodUploadUrl)){
                        $videoFileUrl = $createAssetReq->getVideoFileUrl();
                        if (!empty($videoFileUrl)){
                            $resp = ConcurrentUploadPart::upload($bucket,$objectKey,$videoFileUrl,$vodClient);
                            if ($resp->getAll()['HttpStatusCode'] === 200){
                                $confirmReq = new ConfirmAssetUploadReq();
                                $confirmReq ->setAssetId($vodRsp->{"asset_id"});
                                $confirmReq ->setStatus("CREATED");
                                $confirmRsp = self::ConfirmAssetUpload($confirmReq,$vodClient);
                                return $confirmRsp->getBody();
                            }
                        }
                    }
                    if (!empty($coverUploadUrl)){
                        $coverFileUrl = $createAssetReq->getCoverFileUrl();
                        if (!empty($coverFileUrl)){
                            $resp = ConcurrentUploadPart::upload($bucket,$objectKey,$coverFileUrl,$vodClient);
                            if ($resp->getAll()['HttpStatusCode'] === 200){
                                $confirmReq = new ConfirmAssetUploadReq();
                                $confirmReq ->setAssetId($vodRsp->{"asset_id"});
                                $confirmReq ->setStatus("CREATED");
                                $confirmRsp = self::ConfirmAssetUpload($confirmReq,$vodClient);
                                return $confirmRsp->getBody();
                            }
                        }
                    }
                    if (!empty($subtitleUploadUrl)){
                        $subtitleFileUrl = $createAssetReq->getSubtitleFileUrl();
                        if (!empty($subtitleFileUrl)){
                            $resp = ConcurrentUploadPart::upload($bucket,$objectKey,$subtitleFileUrl,$vodClient);
                            if ($resp->getAll()['HttpStatusCode'] === 200){
                                $confirmReq = new ConfirmAssetUploadReq();
                                $confirmReq ->setAssetId($vodRsp->{"asset_id"});
                                $confirmReq ->setStatus("CREATED");
                                $confirmRsp = self::ConfirmAssetUpload($confirmReq,$vodClient);
                                return $confirmRsp->getBody();
                            }
                        }
                    }
                }else{
                    var_dump($vodRsp);
                }
            }
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return null;
    }

    /**
     * 查询媒资请求
     * @param QueryAssetMetaReq $queryAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function queryAsset(QueryAssetMetaReq $queryAssetReq, VodClient $vodClient){
        $param = $queryAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/info',VERSION_1_0, $vodClient);
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
     * 查询指定媒资详细信息请求
     * @param QueryAssetDetailReq $queryAssetDetailReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function queryAssetDetail(QueryAssetDetailReq $queryAssetDetailReq, VodClient $vodClient){
        $param = $queryAssetDetailReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/details',VERSION_1_0, $vodClient);
        $authAkSkRequest->setQuery($param);
        $authAkSkRequest->sign($vodClient);
        $authAkSkRequest->setRequestUrl();

        try {
            $response = CommonFunctions::http($authAkSkRequest->getRequestUrl(), $authAkSkRequest->getQuery(), $authAkSkRequest->getMethod(),
                $authAkSkRequest->getHeaders());
            return $response;
        } catch (VodException $e) {
            echo $e->getErrorMessage();
        }
        return null;
    }

    /**
     * 查询媒资列表
     * @param QueryAssetListReq $queryAssetListReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function queryAssetList(QueryAssetListReq $queryAssetListReq, VodClient $vodClient){
        $param = $queryAssetListReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/list',VERSION_1_0, $vodClient);
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
     * 查询媒资密钥(终端播放HLS加密视频时，向租户管理系统请求密钥，
     * 租户管理系统先查询其本地有没有已缓存的密钥，没有时则调用此接口向VOD查询)
     * @param QueryAssetCiphersReq $queryAssetCiphersReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function queryAssetCiphers(QueryAssetCiphersReq $queryAssetCiphersReq, VodClient $vodClient){
        $queryAssetCiphersReq->validate();
        $param = $queryAssetCiphersReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_GET);
        $authAkSkRequest->setUri('/asset/ciphers',VERSION_1_0, $vodClient);
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
     * 删除媒资请求
     * @param DeleteAssetReq $deleteAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function deleteAsset(DeleteAssetReq $deleteAssetReq, VodClient $vodClient){
        $deleteAssetReq->validate();
        $param = $deleteAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_DELETE);
        $authAkSkRequest->setUri('/asset',VERSION_1_0, $vodClient);
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
     * 更新媒资属性信息请求
     * @param UpdateAssetMetaReq $updateAssetMetaReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function updateAssetMeta(UpdateAssetMetaReq $updateAssetMetaReq, VodClient $vodClient){
        $param = $updateAssetMetaReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_PUT);
        $authAkSkRequest->setUri('/asset/info',VERSION_1_0, $vodClient);
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
     * 确认媒资上传请求
     * @param ConfirmAssetUploadReq $confirmAssetUploadReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function confirmAssetUpload(ConfirmAssetUploadReq $confirmAssetUploadReq, VodClient $vodClient){
        $confirmAssetUploadReq->validate();
        $param = $confirmAssetUploadReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/status/uploaded',VERSION_1_0, $vodClient);
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
     * 更新媒资请求
     * @param UpdateAssetReq $updateAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function updateAsset(UpdateAssetReq $updateAssetReq, VodClient $vodClient){
        $param = $updateAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_PUT);
        $authAkSkRequest->setUri('/asset',VERSION_1_0, $vodClient);
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
     * 媒资处理请求
     * @param AssetProcessReq $assetProcessReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function processAsset(AssetProcessReq $assetProcessReq, VodClient $vodClient){
        $param = $assetProcessReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/process',VERSION_1_0, $vodClient);
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
     * OBS一键发布桶授权请求
     * @param BucketAuthorizedReq $bucketAuthorizedReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function oBSBucketAuthorized(BucketAuthorizedReq $bucketAuthorizedReq, VodClient $vodClient){
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_PUT);
        $authAkSkRequest->setUri('/asset/authority',VERSION_1_0, $vodClient);
        $authAkSkRequest->setHeaders(array('Content-Type'=>APPLICATION_JSON));
        $authAkSkRequest->setBody(json_encode($bucketAuthorizedReq,JSON_UNESCAPED_UNICODE));
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
     * OBS一键发布请求(需先进行OBS桶授权操作)
     * @param PublishAssetFromObsReq $publishAssetFromObsReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function publishAssetFromObs(PublishAssetFromObsReq $publishAssetFromObsReq, VodClient $vodClient){
        $param = $publishAssetFromObsReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/reproduction',VERSION_1_0, $vodClient);
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
     * 媒资CDN预热请求(需加速域名)
     * @param PreheatingAssetReq $preheatingAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function preheatingAsset(PreheatingAssetReq $preheatingAssetReq, VodClient $vodClient){
        $preheatingAssetReq->validate();
        $param = $preheatingAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/preheating',VERSION_1_0, $vodClient);
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
     * 媒资发布请求
     * @param PublishAssetReq $publishAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function publishAsset(PublishAssetReq $publishAssetReq, VodClient $vodClient){
        $param = $publishAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/status/publish',VERSION_1_0, $vodClient);
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
     * 媒资取消发布请求
     * @param PublishAssetReq $publishAssetReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function unPublishAsset(PublishAssetReq $publishAssetReq, VodClient $vodClient){
        $param = $publishAssetReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/status/unpublish',VERSION_1_0, $vodClient);
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
     * 媒资提取音频请求
     * @param ExtractAudioTaskReq $extractAudioTaskReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function extractAudioTask(ExtractAudioTaskReq $extractAudioTaskReq, VodClient $vodClient){
        $param = $extractAudioTaskReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/extract_audio',VERSION_1_0, $vodClient);
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
     * 媒资审核
     * @param AssetReviewReq $assetReviewReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function createAssetReviewTask(AssetReviewReq $assetReviewReq, VodClient $vodClient){
        $param = $assetReviewReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/review',VERSION_1_0, $vodClient);
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
     * 创建智能封面任务
     * @param CreateSmartCoverReq $createSmartCoverReq
     * @param VodClient $vodClient
     * @return HttpResponse|null
     * @throws VodException
     */
    public function createSmartCoverTask(CreateSmartCoverReq $createSmartCoverReq, VodClient $vodClient){
        $param = $createSmartCoverReq->getSerializedNamedParam();
        $authAkSkRequest = new AuthAkSkRequest();
        $authAkSkRequest->setMethod(HTTP_METHOD_POST);
        $authAkSkRequest->setUri('/asset/smart_cover',VERSION_1_0, $vodClient);
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