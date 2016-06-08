<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/EmailNotification.php";

class UserInvitationEmail extends EmailNotification{

    const SUBJECT = "Convite de cadastro no ";
    const INVITATION_REGISTER_PAGE = "invitation_register";

    private $invitation;
    private $secretaryName;

	public function __construct($user, $invitation, $secretaryName){
        $this->invitation = $invitation;
        $this->secretaryName = $secretaryName;
        parent::__construct($user);
	}

	protected function setSubject(){
        $this->subject = self::SUBJECT.EmailConstants::SENDER_NAME;
	}

	protected function setMessage(){ 

        $invitation = $this->invitation;
        $secretary = $this->secretaryName;
        $userEmail = $this->user()->getEmail();
        $system = EmailConstants::SENDER_NAME;

        $message = "Olá!<br>";
        $message .= "Você foi convidado pelo(a) secretário(a) <i><b>{$secretary}</b></i> para se cadastrar no {$system}! Clique no link abaixo para se cadastrar: <br><br>";
        $message .= "<a href='";
        $message .= $this->getSiteUrl()."?invitation={$invitation}&email={$userEmail}";
        $message .= "'>Cadastrar-se no {$system}.</a>";

        $this->message = $message;
    }

    private function getSiteUrl(){

        $ci =& get_instance();
        $ci->load->helper('url');
        $siteUrl = site_url(self::INVITATION_REGISTER_PAGE);

        return $siteUrl;
    }
}