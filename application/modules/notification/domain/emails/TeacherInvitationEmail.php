<?php

require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";
require_once MODULESPATH."notification/domain/emails/UserInvitationEmail.php";

class TeacherInvitationEmail extends UserInvitationEmail{

    const SUBJECT = "Convite de cadastro no ";
    const INVITATION_REGISTER_PAGE = "invitation_register";

    public function __construct($user, $invitation, $secretaryName){
        parent::__construct($user, $invitation, $secretaryName);
    }

    protected function setMessage(){

        $invitation = $this->invitation;
        $secretary = $this->secretaryName;
        $system = EmailConstants::SENDER_NAME;
        $url = $this->getSiteUrl();

        $message = "Olá!<br><br>";
        $message .= "Você foi convidado pelo(a) secretário(a) <i><b>{$secretary}</b></i> para se cadastrar como <b>Docente</b> do programa <i>Programa de Pós Graduação em Educação - Modalidade Profissional (<b>PPGEMP</b>)</i> no {$system}! ";

        $message .= " Clique no link abaixo para se cadastrar: <br><br>";
        $message .= "<a href='{$url}?invitation={$invitation}'>Cadastrar-se no {$system}.</a>";

        $this->message = $message;
    }

    protected function getSiteUrl(){

        $siteUrl = "https://ppgemp.fe.unb.br/".self::INVITATION_REGISTER_PAGE;

        return $siteUrl;
    }
}