<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class AssetFileData extends BaseRequest implements JsonSerializable {

    private static $videoTypes = array('MP4', 'TS', 'MOV', 'MXF', 'FLV','MPG','WMV');

    private static $audioTypes = array('MP3', 'OGG', 'WAV','WMA','APE','FLAC','AAC','AC3','MMF','AMR','M4A','M4R','WV','MP2');

    private static $coverTypes = array('JPG', 'PNG');

    private $videoFileUrl;

    private $coverFileUrl;

    private $subtitleFileUrl;

    /**
     * 视频文件MD5值
     */
    private $videoMd5;

    /**
     * 视频文件名，可以带后缀，也可以不带后缀。
     */
    private $videoName;

    /**
     * 视频文件类型，当前支持：
     * MP4、TS、MOV、MXF、FLV
     */
    private $videoType;

    /**
     * 封面ID，取值0-7。当前只支持一张封面，只能填0
     */
    private $coverId;

    /**
     * 封面图片格式类型，例如“jgp、png”。上传后封面名称是固定的，后缀名为封面类型的缩写。例如cover0.jpg、cover.png。
     * 如果不指定类型，则封面文件无后缀名
     */
    private $coverType;

    /**
     * 封面文件MD5值
     */
    private $coverMd5;

    public function __set($key,$value){
        $this->$key = $value;
    }

    public function __get($value){
        if(isset($this->$value)){
            return $this->$value;
        }else{
            return null;
        }

    }


    /**
     * @return mixed
     */
    public function getVideoFileUrl()
    {
        return $this->videoFileUrl;
    }

    /**
     * @param mixed $videoFileUrl
     */
    public function setVideoFileUrl($videoFileUrl)
    {
        $this->videoFileUrl = $videoFileUrl;
        $this->serializedNamedParam['video_file_url'] = $videoFileUrl;
    }

    /**
     * @return mixed
     */
    public function getCoverFileUrl()
    {
        return $this->coverFileUrl;
    }

    /**
     * @param mixed $coverFileUrl
     */
    public function setCoverFileUrl($coverFileUrl)
    {
        $this->coverFileUrl = $coverFileUrl;
        $this->serializedNamedParam['cover_file_url'] = $coverFileUrl;
    }

    /**
     * @return mixed
     */
    public function getSubtitleFileUrl()
    {
        return $this->subtitleFileUrl;
    }

    /**
     * @param mixed $subtitleFileUrl
     */
    public function setSubtitleFileUrl($subtitleFileUrl)
    {
        $this->subtitleFileUrl = $subtitleFileUrl;
        $this->serializedNamedParam['subtitle_file_url'] = $subtitleFileUrl;
    }

    /**
     * @return mixed
     */
    public function getVideoMd5()
    {
        return $this->videoMd5;
    }

    /**
     * @param mixed $videoMd5
     */
    public function setVideoMd5($videoMd5)
    {
        $this->videoMd5 = $videoMd5;
        $this->serializedNamedParam['video_md5'] = $videoMd5;
    }

    /**
     * @return mixed
     */
    public function getVideoName()
    {
        return $this->videoName;
    }

    /**
     * @param mixed $videoName
     */
    public function setVideoName($videoName)
    {
        $this->videoName = $videoName;
        $this->serializedNamedParam['video_name'] = $videoName;
    }

    /**
     * @return mixed
     */
    public function getVideoType()
    {
        return $this->videoType;
    }

    /**
     * @param mixed $videoType
     */
    public function setVideoType($videoType)
    {
        $this->videoType = $videoType;
        $this->serializedNamedParam['video_type'] = $videoType;
    }

    /**
     * @return mixed
     */
    public function getCoverId()
    {
        return $this->coverId;
    }

    /**
     * @param mixed $coverId
     */
    public function setCoverId($coverId)
    {
        $this->coverId = $coverId;
        $this->serializedNamedParam['cover_id'] = $coverId;
    }

    /**
     * @return mixed
     */
    public function getCoverType()
    {
        return $this->coverType;
    }

    /**
     * @param mixed $coverType
     */
    public function setCoverType($coverType)
    {
        $this->coverType = $coverType;
        $this->serializedNamedParam['cover_type'] = $coverType;
    }

    /**
     * @return mixed
     */
    public function getCoverMd5()
    {
        return $this->coverMd5;
    }

    /**
     * @param mixed $coverMd5
     */
    public function setCoverMd5($coverMd5)
    {
        $this->coverMd5 = $coverMd5;
        $this->serializedNamedParam['cover_md5'] = $coverMd5;
    }


    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->serializedNamedParam as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

    public function validate()
    {
    }
}