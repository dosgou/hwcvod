<?php
/**
 * 提取音频请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\ExtractAudioTaskReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new ExtractAudioTaskReq();
$req->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req->setFormat('MP3');

$rsp = $vodClient->extractAudioTask($req);
echo $rsp->getBody();