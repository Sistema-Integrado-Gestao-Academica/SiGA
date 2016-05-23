<?php

class StudentRegistrationExceptionOld extends Exception{

    public function __construct($message, $exception_code = 0){
        parent::__construct($message, $exception_code);
    }

}