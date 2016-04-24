<?php

require_once APPPATH."/constants/EmailConstants.php";
require_once APPPATH."/data_types/notification/EmailNotification.php";
require_once APPPATH."/exception/EmailNotificationException.php";

class ConfirmSignUpEmail extends EmailNotification{

    const SUBJECT = "Confirmação de cadastro no ".EmailConstants::SENDER_NAME;

	public function __construct($user){
		parent::__construct($user);
	}

	protected function setSubject(){
        $this->subject = self::SUBJECT; 
	}

	protected function setMessage(){ 
        
        $user = $this->user();
        $userName = $user->getName();

        /**
            Criar atributo activation na classe User
            
            Salvar o activation gerado na hora do cadastro
    
            Montar o link de confirmação

            Enviar o email de confirmação

            Fazer página de recebimento do link de confirmação
        */

        // $activation = $user->activation();

        $message = "Olá, <b>{$userName}</b>. <br>";
        $message = $message."Para efetivar seu cadastro no sistema, clique no link abaixo:";

        $this->message = $message;
    }
}