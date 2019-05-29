<?php
/**
 * 确认媒资上传
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\ConfirmAssetUploadReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new ConfirmAssetUploadReq();
$req->setAssetId("55576e74bfd6d828e8c8d445500bf961");
$req->setStatus("CREATED");

$rsp = $vodClient->confirmAssetUpload($req);
echo $rsp->getBody();