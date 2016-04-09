<?php

require_once APPPATH."/data_types/notification/EmailNotification.php";

class RestorePasswordEmail extends EmailNotification{

	public function __construct($user){
		parent::__construct($user);
		$this->setSubject();	
		$this->setMessage();
	}

	private function setSubject(){

        $this->subject = "Solicitação de recuperação de senha - SiGA"; 
	}

	private function setMessage(){ 
        
        $user = $this->user();
        $newPassword = $user->getPassword();
        $userName = $user->getName();

        $message = "Olá, <b>{$userName}</b>. <br>";
        $message = $message."Esta é uma mensagem automática para a solicitação de nova senha de acesso ao SiGA. <br>";
        $message = $message."Sua nova senha para acesso é: <b>".$newPassword."</b>. <br>";
        $message = $message."Lembramos que para sua segurança ao acessar o sistema com essa senha iremos te redirecionar para a definição de uma nova senha. <br>"; 

        $this->message = $message;
    }
}