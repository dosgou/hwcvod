<?php
/**
 * 创建媒资请求
 */
require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\CreateAssetByFileReq;
use hwcvod\vod\model\Review;
use hwcvod\vod\model\Thumbnail;
use hwcvod\vod\model\SubtitleReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);

$req = new CreateAssetByFileReq();
$review = new Review();
$thumbnail = new Thumbnail();
$subtitle = new SubtitleReq();

$req ->setTitle('测试PHP端SDK创建');
$req ->setDescription('test');
$req ->setCategoryId(-1);
$req ->setVideoName('测试PHP端SDK创建');
$req ->setVideoType('MP4');
$req ->setVideoMd5("Kj/JUwNwn410lwmrJl9X0g==");

$review ->setInterval(10);
$review ->setPorn(0);
$review ->setPolitics(1);
$review ->setTerrorism(1);

$thumbnail ->setType('time');
$thumbnail ->setCoverPosition(1);
$thumbnail ->setFormat(1);
$thumbnail ->setAspectRatio(0);

$subtitle ->setId(1);
$subtitle ->setLanguage('CN');
$subtitle ->setDescription('subtitle test');
$subtitle ->setType('SRT');
$subtitle ->setMd5('TWIb5o8pGiBXlqPdFeCHjQ==');

$req ->setReview($review);
$req ->setThumbnail($thumbnail);
$req ->setSubtitles(array($subtitle));

$rsp = $vodClient->createAssetByFile($req);
echo $rsp->getBody();