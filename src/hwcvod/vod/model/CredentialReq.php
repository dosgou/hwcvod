<?php
namespace hwcvod\vod\model;

use JsonSerializable;

class CredentialReq implements JsonSerializable {

    private $auth;

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
     * CredentialReq constructor.
     * @param $token
     * @param $duration
     */
    public function __construct($token,$duration)
    {
        $auth = new CredentialAuth();
        $identity = new CredentialIdentity();
        $tokens = new Token();
        $auth->setIdentity($identity);
        $tokens->setId($token);
        $tokens->setDurationSeconds($duration);
        $identity->setToken($tokens);
        $this->auth = $auth;
    }


    /**
     * @param mixed $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }


    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
}

class CredentialAuth implements JsonSerializable {

    private $identity;

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }

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
}

class CredentialIdentity implements JsonSerializable
{
    private $methods = ['token'];
    private $token;

    /**
     * @param mixed $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this as $key=>$val){
            if ($val !== null) $data[$key] = $val;
        }
        return $data;
    }
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
}

class Token extends BaseRequest implements JsonSerializable
{
    private $id;
    private $durationSeconds;

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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $durationSeconds
     */
    public function setDurationSeconds($durationSeconds)
    {
        $this->durationSeconds = $durationSeconds;
        $this->serializedNamedParam['duration-seconds'] = $durationSeconds;
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
        if($this->durationSeconds <= 15 * 60 || $this->durationSeconds >= 24 * 60 * 60)
        {
            throw new VodException("VOD.100011001","Duration needs to be 15 minutes to 24 hours");
        }
    }
}