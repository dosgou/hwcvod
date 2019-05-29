<?php
/**
 * 查询源站统计信息
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\QueryStatReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new QueryStatReq();
$req ->setStartTime('20181019040013');
$req ->setEndTime('20181020040013');
$req ->setInterval(3600);

$rsp = $vodClient->queryVodStat($req);
echo $rsp->getBody();