<?php
namespace hwcvod\vod\client;

use hwcvod\exception\VodException;

//代理初始化
define('ENABLE_HTTP_PROXY', false);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8080');
//点播服务域名
define('VOD_HOST', 'vod.cn-north-1.myhuaweicloud.com');
//http相关
define('APPLICATION_JSON', 'application/json');
define('HTTP_METHOD_POST', 'POST');
define('HTTP_METHOD_GET', 'GET');
define('HTTP_METHOD_DELETE', 'DELETE');
define('HTTP_METHOD_PUT', 'PUT');
define('VERSION_1_0', '1.0');
define('VERSION_1_1', '1.1');
define('VIDEO_UPLOAD_URL', 'video_upload_url');
define('COVER_UPLOAD_URL', 'cover_upload_url');
define('SUBTITLE_UPLOAD_URL', 'subtitle_upload_urls');
define('OBS_ENDPOINT', 'obs.myhwclouds.com');

class VodConfig{

    private $ak;

    private $sk;

    private $projectId;

    /**
     * @return mixed
     */
    public function getAk(){
        return $this->ak;
    }

    /**
     * @param mixed $ak
     */
    public function setAk($ak){
        $this->ak = $ak;
    }

    /**
     * @return mixed
     */
    public function getSk(){
        return $this->sk;
    }

    /**
     * @param mixed $sk
     */
    public function setSk($sk){
        $this->sk = $sk;
    }

    /**
     * @return mixed
     */
    public function getProjectId(){
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId){
        $this->projectId = $projectId;
    }

    public function validate(){
        if(empty($this->ak)){
            try {
                throw new VodException('VOD.100011001', "Please configure your ak");
            }catch (VodException $e){
                echo $e->getErrorCode();
                echo $e->getErrorMessage();
            }
        }

        if(empty($this->sk)){
            try {
                throw new VodException('VOD.100011001', "Please configure your sk");
            }catch (VodException $e){
                echo $e->getErrorCode();
                echo $e->getErrorMessage();
            }
        }

        if(empty($this->projectId)){
            try {
                throw new VodException('VOD.100011001', "Please configure your projectId");
            }catch (VodException $e){
                echo $e->getErrorCode();
                echo $e->getErrorMessage();
            }
        }
    }
}