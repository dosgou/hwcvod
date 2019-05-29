<?php
/**
 * 删除媒资请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\DeleteAssetReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new DeleteAssetReq();
$req ->setAssetId('91be18dd302c9aeb0286bd914af17a6e');

$rsp = $vodClient->deleteAssets($req);
echo $rsp->getBody();