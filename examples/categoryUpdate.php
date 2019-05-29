<?php
/**
 * 修改媒资分类
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\EditCategoryReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new EditCategoryReq();
$req ->setId(22803);
$req ->setName('修改的分类');

$rsp = $vodClient->modifyAssetCategory($req);
echo $rsp->getBody();