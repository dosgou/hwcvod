<?php
/**
 * 查询Cdn统计信息
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
$req->setDomain("www.example.com");
$req->setStatType("cdn_bw");

$rsp = $vodClient->queryCdnStat($req);
echo $rsp->getBody();