<?php
namespace hwcvod\exception;

use Exception;

class VodException extends Exception{
    public function __construct($errCode, $errMsg){
        parent::__construct($errMsg);
        $this->errorCode = $errCode;
        $this->errorMessage = $errMsg;
    }
    
    private $errorCode;
    private $errorMessage;
    
    public function getErrorCode(){
        return $this->errorCode;
    }
    
    public function setErrorCode($errorCode){
        $this->errorCode = $errorCode;
    }
    
    public function getErrorMessage(){
        return $this->errorMessage;
    }
    
    public function setErrorMessage($errorMessage){
        $this->errorMessage = $errorMessage;
    }
}
