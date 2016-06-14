<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/EmailNotification.php";

class GroupInvitationEmail extends EmailNotification{

    const SUBJECT = "Convite para participar de um novo grupo no ";
    const GROUP_INVITATION_PAGE = "join_group_invitation";

    private $invitation;
    private $secretaryName;
    private $invitedGroupName;

	public function __construct($user, $invitation, $secretaryName, $invitedGroupName){
        $this->invitation = $invitation;
        $this->secretaryName = $secretaryName;
        $this->invitedGroupName = $invitedGroupName;
        parent::__construct($user);
	}

	protected function setSubject(){
        $this->subject = self::SUBJECT.EmailConstants::SENDER_NAME;
	}

	protected function setMessage(){ 

        $invitation = $this->invitation;
        $group = ucfirst($this->invitedGroupName);
        $secretary = $this->secretaryName;
        $system = EmailConstants::SENDER_NAME;

        $message = "Olá!<br>";
        $message .= "Você foi convidado pelo(a) secretário(a) <i><b>{$secretary}</b></i> para participar do grupo <i><b>{$group}</b></i>! Clique no link abaixo para efetivar seu cadastro no grupo: <br><br>";
        $message .= "<a href='";
        $message .= $this->getSiteUrl()."?invitation={$invitation}";
        $message .= "'>Cadastrar-se no grupo {$group}.</a>";

        $this->message = $message;
    }

    private function getSiteUrl(){

        $ci =& get_instance();
        $ci->load->helper('url');
        $siteUrl = site_url(self::GROUP_INVITATION_PAGE);

        return $siteUrl;
    }
}