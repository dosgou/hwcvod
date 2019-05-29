<?php
/**
 * 查询媒资分类
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\QueryCategoryReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new QueryCategoryReq();
$req->setId(87522);

$rsp = $vodClient->queryAssetCategory($req);
echo $rsp->getBody();