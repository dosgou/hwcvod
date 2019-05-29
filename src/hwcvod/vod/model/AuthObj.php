<?php
namespace hwcvod\vod\model;

use hwcvod\vod\client\VodClient;
use JsonSerializable;

class AuthObj implements JsonSerializable {
    /**
     * auth:{"identity":{},"scope":{}}
     */
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
     * AuthObj constructor.
     * @param $name
     * @param $password
     * @param $domainName
     * @param VodClient $vodClient
     */
    public function __construct($name,$password,$domainName,VodClient $vodClient)
    {
        $auth = new Auth();
        $identity = new Identity();
        $scope = new Scope();
        $passwords = new Password();
        $project = new Project($vodClient->getVodConfig()->getProjectId());
        $user = new User();
        $domain = new Domain();
        $domain->setName($domainName);
        $passwords->setUser($user);
        $user->setName($name);
        $user->setPassword($password);
        $user->setDomain($domain);
        $scope->setProject($project);
        $identity->setPassword($passwords);
        $auth->setIdentity($identity);
        $auth->setScope($scope);
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
class Auth implements JsonSerializable{

    private $identity;
    private $scope;

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param mixed $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
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
class Identity implements JsonSerializable
{
    /**
     * identity : {"methods":["password"],"password":{"user":{"name":"user_name","password":"password","domain":{"name":"domain_name"}}}}
     */

    private $methods = ['password'];
    private $password;

    /**
     * @param mixed $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
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

class Password implements JsonSerializable
{
    /**
     * user : {"name":"user_name","password":"password","domain":{"name":"domain_name"}}
     */
    private $user;

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function validate()
    {
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

class User implements JsonSerializable
{
    /**
     * name : user_name
     * password : password
     * domain : {"name":"domain_name"}
     */
    private $name;
    private $password;
    private $domain;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
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

class Domain implements JsonSerializable
{
    /**
     * name : domain_name
     */
    private $name;

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
class Scope implements JsonSerializable
{
    /**
     * project : {"id":"projectId"}
     */
    private $project;

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
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
class Project implements JsonSerializable
{
    /**
     * id : project
     */
    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Project constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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