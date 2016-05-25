<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/EmailNotification.php";

class ConfirmSignUpEmail extends EmailNotification{

    const SUBJECT = "Confirmação de cadastro no ";
    const CONFIRM_PAGE = "confirm_register";

    private $activation;

	public function __construct($user, $activation){
        $this->setActivation($activation);
        parent::__construct($user);
	}

	protected function setSubject(){
        $this->subject = self::SUBJECT.EmailConstants::SENDER_NAME;
	}

	protected function setMessage(){ 
        
        $user = $this->user();
        $userName = $user->getName();
        $userId = $user->getId();
        $activation = $this->activation;
        
        $initializationVector = bin2hex(openssl_random_pseudo_bytes(8));
        $initializationVector = $initializationVector.$userId;
        
        $encryptedUser = openssl_encrypt($userId, "AES128", $activation, $options = 0, $initializationVector);

        $message = "Olá, <b>{$userName}</b>. <br>";
        $message .= "Para confirmar seu cadastro no sistema, clique no link abaixo: <br><br>";
        $message .= "<a href='";
        $message .= $this->getSiteUrl()."?k={$activation}&u={$encryptedUser}&i={$initializationVector}";
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