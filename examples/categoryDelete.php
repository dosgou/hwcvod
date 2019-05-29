<?php
/**
 * 删除媒资分类
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\DeleteCategoryReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new DeleteCategoryReq();
$req->setId(87529);

$rsp = $vodClient->deleteAssetCategory($req);
echo $rsp->getBody();