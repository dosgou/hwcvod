<?php
/**
 * 创建媒资分类
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\CreateCategoryReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new CreateCategoryReq();
$req->setName('新分类00');
$req->setParentId(0);

$rsp = $vodClient->createAssetCategory($req);
echo $rsp->getBody();