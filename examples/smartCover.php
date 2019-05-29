<?php
require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\CreateSmartCoverReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new CreateSmartCoverReq();
$req ->setAssetId('4d520cd12063953048ffa742ce4ce51b');

$rsp = $vodClient->createSmartCoverTask($req);
echo $rsp->getBody();