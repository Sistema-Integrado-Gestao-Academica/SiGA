<?php

require_once MODULESPATH."notification/domain/EmailNotification.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";

class SecretaryEmailNotification extends EmailNotification{

    const NULL_GUEST_USERS = "A quantidade de usuários convidados não pode ser nula";
    const NULL_DOCUMENTS_REQUEST = "A quantidade de solicitações de documento não pode ser nula";

    const SECRETARY_SUBJECT = "Secretaria - SiGA";

    private $guestUsers;
    private $documentsRequest;

	public function __construct($user, $quantityOfGuestUsers, $quantityOfDocumentsRequest){
        $this->setGuestUsers($quantityOfGuestUsers);
        $this->setDocumentsRequest($quantityOfDocumentsRequest);
        parent::__construct($user);
	}

	protected function setSubject(){

        $this->subject = self::SECRETARY_SUBJECT; 
	}

	protected function setMessage(){ 
        
        $user = $this->user();
        $userName = $user->getName();
        $quantityOfGuestUsers = $this->getGuestUsers();
        $quantityOfDocumentsRequest = $this->getDocumentsRequest();
        $message = "";

        $validGuestUsers = !is_null($quantityOfGuestUsers) && !empty($quantityOfGuestUsers);
        $validDocumentsRequest = !is_null($quantityOfDocumentsRequest) && !empty($quantityOfDocumentsRequest);

        if($validGuestUsers && $validDocumentsRequest){
            $message = "Olá, <b>{$userName}</b>. <br>";
            $message = $message."Esta é uma mensagem automática para informar a situação atual da Secretaria Acadêmica. <br>";
            $message = $message."Há <b>".$quantityOfGuestUsers."</b> usuário(s) sem matrícula em um curso. <br>";
            $message = $message."Há <b>".$quantityOfDocumentsRequest."</b> documento(s) solicitado(s). <br>";
        }

        $this->message = $message;
    }

    private function setGuestUsers($quantity){

        if(!is_null($quantity)){
            $this->guestUsers = $quantity;
        }
        else{
            throw new EmailNotificationException(self::NULL_GUEST_USERS);
            
        }
    }

    private function setDocumentsRequest($quantity){

        if(!is_null($quantity)){
            $this->documentsRequest = $quantity;
        }
        else{
            throw new EmailNotificationException(self::NULL_DOCUMENTS_REQUEST);
        }
    }

    public function getGuestUsers(){
        return $this->guestUsers;
    }

    public function getDocumentsRequest(){
        return $this->documentsRequest;
    }
}