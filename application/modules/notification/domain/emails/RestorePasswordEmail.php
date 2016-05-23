<?php

require_once MODULESPATH."notification/domain/EmailNotification.php";

class RestorePasswordEmail extends EmailNotification{

    const RESTORE_PASSWORD_SUBJECT = "Solicitação de recuperação de senha - SiGA";

	public function __construct($user){
		parent::__construct($user);
	}

	protected function setSubject(){

        $this->subject = self::RESTORE_PASSWORD_SUBJECT; 
	}

	protected function setMessage(){ 
        
        $user = $this->user();
        $newPassword = $user->getPassword();
        $userName = $user->getName();
        $message = "";

        if(!is_null($newPassword) && !empty($newPassword)){
            $message = "Olá, <b>{$userName}</b>. <br>";
            $message = $message."Esta é uma mensagem automática para a solicitação de nova senha de acesso ao SiGA. <br>";
            $message = $message."Sua nova senha para acesso é: <b>".$newPassword."</b>. <br>";
            $message = $message."Lembramos que para sua segurança ao acessar o sistema com essa senha iremos te redirecionar para a definição de uma nova senha. <br>"; 
        }

        $this->message = $message;
    }
}