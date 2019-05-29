<?php
/**
 * 查询TopN信息
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\QueryTopStatReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new QueryTopStatReq();
$req ->setDomain("www.test.com");
$req ->setDate("20181010");

$rsp = $vodClient->queryTopStat($req);
echo $rsp->getBody();