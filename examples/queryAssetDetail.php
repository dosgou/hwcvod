<?php
/**
 * 查询媒资详细信息请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\QueryAssetDetailReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new QueryAssetDetailReq();
$req ->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req ->setCategories(array('base_info','review_info'));

$rsp = $vodClient->queryAssetDetail($req);
echo $rsp->getBody();