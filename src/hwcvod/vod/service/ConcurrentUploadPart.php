<?php
namespace hwcvod\vod\service;

use hwcvod\obs\Common\ObsException;
use hwcvod\vod\client\VodClient;
use hwcvod\vod\model\InitiateMultipartUploadReq;
use hwcvod\vod\model\MultipartUploadReq;
use hwcvod\vod\model\ListPartsReq;
use hwcvod\vod\model\CompleteMultipartUploadReq;
use hwcvod\obs\s3\ObsClient;


class ConcurrentUploadPart{

    /*
     * Constructs a obs client instance with your account for accessing OBS
     */
    public static function upload($bucketName,$objectKey,$filePath,VodClient $vodClient)
    {
        /**
         * 中文路径解决方案
         */
        $fileName=iconv('UTF-8','GBK',$filePath);

        $obsClient = ObsClient::factory ( [
            'key' => $vodClient->getVodConfig()->getAk(),
            'secret' => $vodClient->getVodConfig()->getSk(),
            'endpoint' => OBS_ENDPOINT,
            'socket_timeout' => 30,
            'connect_timeout' => 10
        ] );
        try
        {
            /*
             * AuthUrl
             */
            $initiateReq = new InitiateMultipartUploadReq();
            $initiateReq->setContentType("binary/octet-stream");
            $initiateReq->setObjectKey($objectKey);
            $initiateReq->setBucket($bucketName);
            $initiateRsp = UploadService::getInstance()->InitiateMultipartUpload($initiateReq, $vodClient);
            $initiateSignParam = self::getSignParams($initiateRsp);

            printf("begin initiateMultipartUpload");
            $resp = $obsClient -> initiateMultipartUpload(['Bucket' => $bucketName, 'Key' => $objectKey],$initiateSignParam);

            $uploadId = $resp['UploadId'];
            printf("Claiming a new upload id %s\n\n", $uploadId);

            $partSize = 5 * 1024 * 1024;
            $fileLength = filesize($fileName);

            $partCount = $fileLength % $partSize === 0 ?  intval($fileLength / $partSize) : intval($fileLength / $partSize) + 1;

            if($partCount > 10000){
                throw new \RuntimeException('Total parts count should not exceed 10000');
            }

            printf("Total parts count %d\n\n", $partCount);
            $parts = [];
            $promise = null;
            /*
             * Upload multiparts to your bucket
             */
            printf("Begin to upload multiparts to OBS from a file\n\n");
            $stream = fopen($fileName,'r+');
            for($i = 0; $i < $partCount; $i++){
                $offset = $i * $partSize;
                $currPartSize = ($i + 1 === $partCount) ? $fileLength - $offset : $partSize;
                $partNumber = $i + 1;
                $uploadReq = new MultipartUploadReq();
                $uploadReq->setBucket($bucketName);
                $uploadReq->setObjectKey($objectKey);
                $uploadReq->setUploadId($uploadId);
                $uploadReq->setPartNumber($partNumber);

                rewind($stream);
                fseek($stream,$offset);
                $tempFilePath = tempnam(__DIR__,"temp");
                $tempFile = fopen($tempFilePath,'w+');
                fwrite($tempFile,fread($stream,$currPartSize));
                $md5 = base64_encode(md5_file($tempFilePath,true));
                fclose($tempFile);
                unlink($tempFilePath);
                $uploadReq->setContentMd5($md5);
                $uploadRsp = UploadService::getInstance()->MultipartUpload($uploadReq, $vodClient);
                $uploadSignParam = self::getSignParams($uploadRsp);

                $p = $obsClient -> uploadPartAsync([
                    'Bucket' => $bucketName,
                    'Key' => $objectKey,
                    'UploadId' => $uploadId,
                    'PartNumber' => $partNumber,
                    'SourceFile' => $fileName,
                    'Offset' => $offset,
                    'PartSize' => $currPartSize,
                    'Content-Md5'=>$uploadReq->getContentMd5(),
                    'Content-Type'=>'application/octet-stream'
                ],$uploadSignParam,function($exception, $resp) use(&$parts, $partNumber) {
                    $parts[] = ['PartNumber' => $partNumber, 'ETag' => $resp['ETag']];
                    printf ( "Part#" . strval ( $partNumber ) . " done\n\n" );
                });

                if($promise === null){
                    $promise = $p;
                }
            }
            fclose($stream);

            /*
             * Waiting for all parts finished
             */
            $promise -> wait();

            usort($parts, function($a, $b){
                if($a['PartNumber'] === $b['PartNumber']){
                    return 0;
                }
                return $a['PartNumber'] > $b['PartNumber'] ? 1 : -1;
            });

            /*
             * Verify whether all parts are finished
             */
            if(count($parts) !== $partCount){
                throw new \RuntimeException('Upload multiparts fail due to some parts are not finished yet');
            }


            printf("Succeed to complete multiparts into an object named %s\n\n", $objectKey);

            /*
             * View all parts uploaded recently
             */
            printf("Listing all parts......\n");

            $listPartsReq = new ListPartsReq();
            $listPartsReq->setUploadId($uploadId);
            $listPartsReq->setObjectKey($objectKey);
            $listPartsReq->setBucket($bucketName);
            $listPartsRsp = UploadService::getInstance()->ListParts($listPartsReq, $vodClient);
            $listSignParam = self::getSignParams($listPartsRsp);

            $resp = $obsClient -> listParts(['Bucket' => $bucketName, 'Key' => $objectKey, 'UploadId' => $uploadId],$listSignParam);
            foreach ($resp['Parts'] as $part)
            {
                printf("\tPart#%d, ETag=%s\n", $part['PartNumber'], $part['ETag']);
            }
            printf("\n");


            /*
             * Complete to upload multiparts
             */
            $completeUploadPartsReq = new CompleteMultipartUploadReq();
            $completeUploadPartsReq->setUploadId($uploadId);
            $completeUploadPartsReq->setObjectKey($objectKey);
            $completeUploadPartsReq->setBucket($bucketName);
            $completeUploadPartsRsp= UploadService::getInstance()->CompleteMultipartUpload($completeUploadPartsReq, $vodClient);
            $completeSignParam = self::getSignParams($completeUploadPartsRsp);

            $resp = $obsClient->completeMultipartUpload([
                'Bucket' => $bucketName,
                'Key' => $objectKey,
                'UploadId' => $uploadId,
                'Parts'=> $parts
            ],$completeSignParam);
            return $resp;
        } catch ( ObsException $e ) {
            echo 'Response Code:' . $e->getStatusCode () . PHP_EOL;
            echo 'Error Message:' . $e->getExceptionMessage () . PHP_EOL;
            echo 'Error Code:' . $e->getExceptionCode () . PHP_EOL;
            echo 'Request ID:' . $e->getRequestId () . PHP_EOL;
            echo 'Exception Type:' . $e->getExceptionType () . PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally{
            $obsClient->close ();
        }
        return null;
    }

    static function getSignParams($rsp){
        $signRsp = json_decode($rsp->getBody(),false);
        $signStr = $signRsp->{'sign_str'};
        $args = substr($signStr,stripos($signStr,'?')+1,strlen($signStr));
        $signParams = explode('&',$args);
        if (in_array('uploads',$signParams)){
            array_shift($signParams);
        }
        $signParam = [];
        foreach ($signParams as $date){
            $ikv = explode('=',$date);
            $signParam[$ikv[0]]=$ikv[1];
        }
        return $signParam;
    }
}