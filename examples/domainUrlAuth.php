<?php
/**
 * 域名key防盗链
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\CreateDomainAuthInfoReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new CreateDomainAuthInfoReq();

$req->setKey("myKey");
$req->setDomainName("198.cdn-vod.huaweicloud.com");
$req->setOriginalUrl("https://198.cdn-vod.huaweicloud.com/asset/415a0be2400c010316fcecb7a334390b/cover/Cover0.jpg");

$rsp = $vodClient->createDomainAuthInfoUrl($req);

echo $rsp->getUrl();