<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/EmailNotification.php";

class GeneralEmail extends EmailNotification{

    private $params;
    private $handle;

    public function __construct($user, Array $params=array(), $paramsHandle){
        $params['user'] = $user;
        $this->params = $params;
        $this->handle = $paramsHandle; // Should be a function to handle params and return the email message
        parent::__construct($user);
    }

    protected function setSubject(){
        $this->subject = $this->params['subject'];
    }

    protected function setMessage(){
        $handler = $this->handle;
        $this->message = $handler($this->params);
    }
}