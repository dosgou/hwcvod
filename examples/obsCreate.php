<?php
/**
 * OBS转存方式创建媒资
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\PublishAssetFromObsReq;
use hwcvod\vod\model\ObsObjInfo;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);

$vodClient = new VodClient($vodConfig);
$req = new PublishAssetFromObsReq();

$obsObj = new ObsObjInfo();
$obsObj->setBucket('hellocdj');
$obsObj->setLocation('southchina');
$obsObj->setObject('abc/aaaa.mp4');
$req->setInput($obsObj);
$req->setTitle('PHP测试OBS转存');
$req->setDescription('20180114');
$req->setCategoryId(-1);
$req->setTags('test');
$req->setVideoType('MP4');
$req->setAutoPublish(1);
//$req ->setTemplateGroupName('yunlongceshi1');

$rsp = $vodClient->publishAssetFromObs($req);
echo $rsp->getBody();


