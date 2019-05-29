<?php
namespace hwcvod\vod\model;

class QueryDomainReq{
    //域名列表，多个域名以逗号（半角）分隔,不填或填ALL表示查询名下全部域名.一次最多查询100个域名。
    private $domain;

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
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function queryDomainArray(){
        $domainArray = [];
        if(!empty($this->domain))
        {
            $domainArray['domain'] = $this->domain;
        }
        return $domainArray;
    }
}