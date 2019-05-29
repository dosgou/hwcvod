<?php
/**
 * 媒资处理请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\AssetProcessReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new AssetProcessReq();
$req ->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req ->setTemplateGroupName('system_template_group');

$rsp = $vodClient->processAsset($req);
echo $rsp->getBody();