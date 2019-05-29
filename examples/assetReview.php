<?php
/**
 * 媒资审核
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodClient;
use hwcvod\vod\client\VodConfig;
use hwcvod\vod\model\Review;
use hwcvod\vod\model\AssetReviewReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new AssetReviewReq();
$review = new Review();
$review->setInterval(5);
$review->setPorn(-1);

$req->setAssetId('55576e74bfd6d828e8c8d445500bf961');
$req->setReview($review);

$rsp = $vodClient->createAssetReviewTask($req);
echo $rsp->getBody();