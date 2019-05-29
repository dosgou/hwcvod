<?php
/**
 * OBS桶授权请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\BucketAuthorizedReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new BucketAuthorizedReq();
$req ->setProjectId('14ce1d4437164aba8b364ce15866154e');
$req ->setBucket('obs-wsl');
$req ->setOperation('1');

$rsp = $vodClient->bucketAuthorized($req);
echo $rsp->getStatus();

