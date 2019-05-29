<?php
/**
 * 更新媒资请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\UpdateAssetMetaReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new UpdateAssetMetaReq();
$req ->setTitle('PHP更新媒资操作');
$req ->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req ->setDescription('PHP更新媒资操作');

$rsp = $vodClient->updateAssetMeta($req);
echo $rsp->getStatus();