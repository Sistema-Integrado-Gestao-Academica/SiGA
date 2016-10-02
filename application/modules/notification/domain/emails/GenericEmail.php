<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/EmailNotification.php";

class GenericEmail extends EmailNotification{

    private $params;
    private $handle;

    public function __construct($user, Array $params=array(), $paramsHandle){
        $params['user'] = $user;
        $this->params = $params;
        $this->handle = $paramsHandle; // Should be a function to handle params and return the email message
        parent::__construct($user);
    }

    protected function setSubject(){
        if(array_key_exists('subject', $this->params)){
            $this->subject = $this->params['subject'];
        }else{
            throw new EmailNotificationException("Missing 'subject' on params array to GenericEmail");
        }
    }

    protected function setMessage(){
        $handler = $this->handle;
        $this->message = $handler($this->params);
    }
}