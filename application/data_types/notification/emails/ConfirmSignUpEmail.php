<?php

require_once APPPATH."/constants/EmailConstants.php";
require_once APPPATH."/data_types/notification/EmailNotification.php";
require_once APPPATH."/exception/EmailNotificationException.php";

class ConfirmSignUpEmail extends EmailNotification{

    const SUBJECT = "Confirmação de cadastro no ";
    const CONFIRM_PAGE = "confirm_register";

    private $activation;

	public function __construct($user, $activation){
		parent::__construct($user);
        $this->setActivation($activation);
        $this->setSubject();    
        $this->setMessage();
	}

	private function setSubject(){
        $this->subject = self::SUBJECT.EmailConstants::SENDER_NAME;
	}

	private function setMessage(){ 
        
        $user = $this->user();
        $userName = $user->getName();
        $userId = $user->getId();
        $activation = $this->activation;
        $encryptedUser = openssl_encrypt($userId, "AES128", $activation);

        $message = "Olá, <b>{$userName}</b>. <br>";
        $message .= "Para confirmar seu cadastro no sistema, clique no link abaixo: <br><br>";
        $message .= "<a href='";
        $message .= $this->getSiteUrl()."?k={$activation}&u={$encryptedUser}";
        $message .= "'>Confirmar cadastro no ".EmailConstants::SENDER_NAME.".</a>";

        $this->message = $message;
    }

    private function setActivation($activation){
        $this->activation = $activation;
    }

    private function getSiteUrl(){

        $ci =& get_instance();
        $ci->load->helper('url');
        $siteUrl = site_url(self::CONFIRM_PAGE);

        return $siteUrl;
    }
}