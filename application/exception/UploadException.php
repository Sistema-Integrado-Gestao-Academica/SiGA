<?php

class UploadException extends Exception{

    private $errorData;

    public function __construct($message, $errorData=FALSE, $exception_code = 0){
        parent::__construct($message, $exception_code);
        $this->setErrorData($errorData);
    }

    public function setErrorData($errorData){
        $error = $errorData;
        if(!is_array($errorData) && !empty($errorData)){
            $error = str_replace("<p>", "", $errorData);
            $error = str_replace("</p>", "", $error);
        }
        $this->errorData = $error;
    }

    public function getErrorData(){
        return $this->errorData;
    }

}