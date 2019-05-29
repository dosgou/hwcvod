<?php
/**
 * 创建媒资并上传请求
 */

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/init.php';

use hwcvod\vod\client\VodConfig;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\CreateAssetByFileReq;

$vodConfig = new VodConfig();
$vodConfig->setAk(AK);
$vodConfig->setSk(SK);
$vodConfig->setProjectId(PID);
$vodClient = new VodClient($vodConfig);
$req = new CreateAssetByFileReq();
//字幕参数
/*$subtitle = new SubtitleReq();
$subtitle ->setId(1);
$subtitle ->setLanguage('CN');
$subtitle ->setDescription('php subtitle test');
$subtitle ->setType('SRT');*/
$req ->setTitle('测试上传视频');
$req ->setDescription('des');
$req ->setCategoryId(-1);
$req ->setVideoName('测试上传视频');
//媒资类型
$req ->setVideoType('MP4');
//封面类型
$req ->setCoverType('JPG');
//要上传的本地媒资、封面和字幕文件地址
$req ->setVideoFileUrl("D:\some.mp4");
$req ->setCoverFileUrl("D:\\some.jpg");
//设置转码模板
$req ->setTemplateGroupName("system_template_group");
//$req ->setSubtitleFileUrl("D:\测试视频\\test.srt");
//$req ->setSubtitles([$subtitle]);

$rsp = $vodClient->createAssetByFileAuto($req);
echo $rsp;