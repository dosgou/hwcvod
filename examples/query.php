<?php
require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\QueryAssetMetaReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new QueryAssetMetaReq();
$req ->setAssetId(array('55576e74bfd6d828e8c8d445500bf961'));
//$req ->setStatus(['PUBLISHED','DELETED']);
//$req->setPage(1);
//$req->setSize(2);

$rsp = $vodClient->queryAssetMeta($req);
echo $rsp->getBody();