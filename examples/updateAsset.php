<?php
/**
 * 更新媒资请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\UpdateAssetReq;
use hwcvod\vod\model\SubtitleReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new UpdateAssetReq();
$subtitle = new SubtitleReq();
$req ->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req ->setCoverId(0);
$req ->setCoverType('JPG');
$subtitle ->setId(1);
$subtitle ->setLanguage('CN');
$subtitle ->setDescription('subtitle test');
$subtitle ->setType('SRT');
$subtitle ->setMd5('SqcyFjJZoDZaP8oKIY6rgQ==');
$req ->setSubtitles(array($subtitle));

$rsp = $vodClient->updateAsset($req);
echo $rsp->getBody();