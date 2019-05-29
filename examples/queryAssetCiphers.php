<?php
/**
 * 查询媒资密钥
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\QueryAssetCiphersReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new QueryAssetCiphersReq();
$req ->setAssetId('55576e74bfd6d828e8c8d445500bf961');

$rsp = $vodClient->queryAssetCiphers($req);
echo $rsp->getBody();