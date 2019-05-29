<?php
namespace hwcvod\vod\client;

use hwcvod\vod\model\CreateAssetByFileReq;
use hwcvod\vod\model\QueryAssetCiphersReq;
use hwcvod\vod\model\QueryAssetMetaReq;
use hwcvod\vod\model\QueryAssetDetailReq;
use hwcvod\vod\model\PreheatingAssetReq;
use hwcvod\vod\model\QueryAssetListReq;
use hwcvod\vod\model\DeleteAssetReq;
use hwcvod\vod\model\QueryDomainReq;
use hwcvod\vod\model\UpdateAssetMetaReq;
use hwcvod\vod\model\AssetProcessReq;
use hwcvod\vod\model\ConfirmAssetUploadReq;
use hwcvod\vod\model\UpdateAssetReq;
use hwcvod\vod\model\PublishAssetFromObsReq;
use hwcvod\vod\model\PublishAssetReq;
use hwcvod\vod\model\ExtractAudioTaskReq;
use hwcvod\vod\model\AssetReviewReq;
use hwcvod\vod\model\QueryCategoryReq;
use hwcvod\vod\model\CreateCategoryReq;
use hwcvod\vod\model\QueryTopStatReq;
use hwcvod\vod\model\QueryStatReq;
use hwcvod\vod\model\BucketAuthorizedReq;
use hwcvod\vod\model\EditCategoryReq;
use hwcvod\vod\model\CreateDomainAuthInfoReq;
use hwcvod\vod\model\DeleteCategoryReq;
use hwcvod\vod\model\CreateSmartCoverReq;
use hwcvod\exception\VodException;
use hwcvod\vod\service\AssetService;
use hwcvod\vod\service\CategoryService;
use hwcvod\vod\service\IamService;
use hwcvod\vod\service\SummaryService;
use hwcvod\vod\service\DomainUrlAuthService;

class VodClient{
    private $vodConfig;

    function __construct(VodConfig $vodConfig){
        $this->vodConfig = $vodConfig;
        $this->validate();
        $vodConfig->validate();
    }

    /**
     * @return mixed
     */
    public function getVodConfig(){
        return $this->vodConfig;
    }

    /**
     * @param mixed $vodConfig
     */
    public function setVodConfig($vodConfig){
        $this->vodConfig = $vodConfig;
    }

    /**
     * 以上传文件方式创建媒资，带上传操作
     * @param CreateAssetByFileReq $req
     * @return null
     * @throws VodException
     */
    public function createAssetByFileAuto(CreateAssetByFileReq $req) {
        return AssetService::getInstance()->createAssetByFileAuto($req, $this);
    }


    /**
     * 以上传文件方式创建媒资，仅创建媒资，需要租户自行调用接口进行上传和确认上传等操作
     * @param CreateAssetByFileReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function createAssetByFile(CreateAssetByFileReq $req) {
        return AssetService::getInstance()->createAssetByFile($req, $this);
    }

    /**
     * 确认媒资上传，要求先将媒资文件先上传到OBS
     * @param ConfirmAssetUploadReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function confirmAssetUpload(ConfirmAssetUploadReq $req){
        return AssetService::getInstance()->confirmAssetUpload($req, $this);
    }

    /**
     * 更新媒资信息
     * @param UpdateAssetMetaReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function updateAssetMeta(UpdateAssetMetaReq $req){
        return AssetService::getInstance()->updateAssetMeta($req, $this);
    }

    /**
     * 媒资发布，支持批量发布
     * @param PublishAssetReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function publishAssets(PublishAssetReq $req){
        return AssetService::getInstance()->publishAsset($req, $this);
    }

    /**
     * 媒资取消发布，支持批量取消
     * @param PublishAssetReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function unPublishAssets(PublishAssetReq $req){
        return AssetService::getInstance()->unpublishAsset($req, $this);
    }

    /**
     * 更新媒资
     * @param UpdateAssetReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function updateAsset(UpdateAssetReq $req){
        return AssetService::getInstance()->updateAsset($req, $this);
    }

    /**
     * 删除媒资，支持批量删除
     * @param DeleteAssetReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function deleteAssets(DeleteAssetReq $req){
        return AssetService::getInstance()->deleteAsset($req, $this);
    }

    /**
     * 媒资处理，包括转码、添加水印、截图等处理
     * @param AssetProcessReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function processAsset(AssetProcessReq $req){
        return AssetService::getInstance()->processAsset($req, $this);
    }

    /**
     * 查询媒资的具体信息
     * @param QueryAssetDetailReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function queryAssetDetail(QueryAssetDetailReq $req){
        return AssetService::getInstance()->queryAssetDetail($req, $this);
    }

    /**
     * 查询媒资列表，可以根据查询条件进行媒资查询
     * @param QueryAssetListReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function queryAssetList(QueryAssetListReq $req){
        return AssetService::getInstance()->queryAssetList($req, $this);
    }

    /**
     * 查询媒资信息，可以根据查询条件进行媒资查询
     * @param QueryAssetMetaReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function queryAssetMeta(QueryAssetMetaReq $req){
        return AssetService::getInstance()->queryAsset($req, $this);
    }

    /**
     * 创建媒资分类
     * @param CreateCategoryReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function createAssetCategory(CreateCategoryReq $req){
        return CategoryService::getInstance()->createAssetCategory($req, $this);
    }

    /**
     * 修改媒资分类
     * @param EditCategoryReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function modifyAssetCategory(EditCategoryReq $req){
        return CategoryService::getInstance()->updateAssetCategory($req, $this);
    }

    /**
     * 查询媒资分类
     * @param QueryCategoryReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function queryAssetCategory(QueryCategoryReq $req){
        return CategoryService::getInstance()->queryAssetCategory($req, $this);
    }

    /**
     * 删除媒资分类
     * @param DeleteCategoryReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function deleteAssetCategory(DeleteCategoryReq $req){
        return CategoryService::getInstance()->deleteAssetCategory($req, $this);
    }

    /**
     * 根据域名设置防盗链后，调用该接口获取带有鉴权信息的URL
     * @param CreateDomainAuthInfoReq $req
     * @return \hwcvod\vod\model\CreateAuthInfoRsp
     */
    public function createDomainAuthInfoUrl(CreateDomainAuthInfoReq $req){
        return DomainUrlAuthService::getInstance()->createAuthInfoUrl($req, $this);
    }


    /**
     * @param $username
     * @param $password
     * @param $domainName
     * @param $duration
     * @return null
     */
    public function requestTemporaryCredential($username, $password, $domainName, $duration){
        return IamService::getInstance()->requestTemporaryCredential($username, $password, $domainName, $duration, $this);
    }

    /**
     * 创建媒资：OBS转存方式
     * @param PublishAssetFromObsReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function publishAssetFromObs(PublishAssetFromObsReq $req){
        return AssetService::getInstance()->publishAssetFromObs($req, $this);
    }

    /**
     * 一键发布之前先获取授权
     * @param BucketAuthorizedReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function bucketAuthorized(BucketAuthorizedReq $req){
        return AssetService::getInstance()->oBSBucketAuthorized($req, $this);
    }

    /**
     * 提取音频任务调用
     * @param ExtractAudioTaskReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function extractAudioTask(ExtractAudioTaskReq $req){
        return AssetService::getInstance()->extractAudioTask($req, $this);
    }

    /**
     * 创建媒资审核任务调用
     * @param AssetReviewReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function createAssetReviewTask(AssetReviewReq $req){
        return AssetService::getInstance()->createAssetReviewTask($req, $this);
    }

    /**
     * 查询cdn信息
     * @param QueryStatReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function queryCdnStat(QueryStatReq $req){
        return SummaryService::getInstance()->queryCdnStat($req,$this);
    }

    /**
     * 查询源站信息
     * @param QueryStatReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function queryVodStat(QueryStatReq $req){
        return SummaryService::getInstance()->queryVodStat($req,$this);
    }

    /**
     * 查询Top信息
     * @param QueryTopStatReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function queryTopStat(QueryTopStatReq $req){
        return SummaryService::getInstance()->queryTopStat($req,$this);
    }

    /**
     * 查询域名信息
     * @param QueryDomainReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     */
    public function queryDomainStat(QueryDomainReq $req){
        return SummaryService::getInstance()->queryDomain($req,$this);
    }

    /**
     * 查询媒资密钥信息
     * @param QueryAssetCiphersReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function queryAssetCiphers(QueryAssetCiphersReq $req){
        return AssetService::getInstance()->queryAssetCiphers($req,$this);
    }

    /**
     * 创建媒资预热任务
     * @param PreheatingAssetReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function preheatAsset(PreheatingAssetReq $req){
        return AssetService::getInstance()->preheatingAsset($req,$this);
    }

    /**
     * 创建智能封面任务
     * @param CreateSmartCoverReq $req
     * @return \hwcvod\generalRequest\HttpResponse|null
     * @throws VodException
     */
    public function createSmartCoverTask(CreateSmartCoverReq $req){
        return AssetService::getInstance()->createSmartCoverTask($req,$this);
    }

    /**
     *
     */
    private function validate(){
        if(empty($this->vodConfig)) {
            try {
                throw new VodException('VOD.100011001', "Please configure vodConfig");
            }catch (VodException $e){
                echo $e->getErrorCode();
                echo $e->getErrorMessage();
            }
        }
    }
}