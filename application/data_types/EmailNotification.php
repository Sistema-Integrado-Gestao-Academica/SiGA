<?php

require_once APPPATH."/exception/EmailNotificationException.php";

class EmailNotification{

	// Error messages 
	const EMPTY_NAME = "O nome não pode ser nulo nem vazio.";
	const EMPTY_EMAIL = "O email não pode ser nulo nem vazio.";
	const INVALID_EMAIL = "Email inválido.";
	const EMPTY_SUBJECT = "O assunto não pode ser nulo nem vazio.";
	const EMPTY_MESSAGE = "A mensagem não pode ser nula nem vazia.";

	// Default values
	const SMTP_SECURE = "ssl";
	const PORT = "465";
	const CHARSET = "UTF-8";

	private $senderName;
	private $senderEmail;
	private $receiverName;
	private $receiverEmail;
	private $subject;
	private $message;


	public function __construct($senderName, $senderEmail, $receiverName, $receiverEmail, $subject, $message){

		$this->setSenderName($senderName);
		$this->setSenderEmail($senderEmail);
		$this->setReceiverName($receiverName);
		$this->setReceiverEmail($receiverEmail);
		$this->setSubject($subject);
		$this->setMessage($message);
	}

	private function setSenderName($name){
		
		if(!is_null($name) && !empty($name)){
			$this->senderName = $name;
		} 
		else{
			throw new EmailNotificationException(self::EMPTY_NAME);
			
		}

	}


	private function setReceiverName($name){
		
		if(!is_null($name) && !empty($name)){
			$this->receiverName = $name;
		} 
		else{
			throw new EmailNotificationException(self::EMPTY_NAME);
			
		}
	}


	private function setSenderEmail($email){
		
		$validEmail = $this->validEmail($email);
		$isNotEmpty = !is_null($email) && !empty($email);

		if($validEmail && $isNotEmpty){
			$this->senderEmail = $email;
		} 
		else{
			if(is_null($email) || empty($email)){
				throw new EmailNotificationException(self::EMPTY_EMAIL);
			}
			else{

				throw new EmailNotificationException(self::INVALID_EMAIL);
			}
			
		}

	}

	private function setReceiverEmail($email){
		
		$validEmail = $this->validEmail($email);
		$isNotEmpty = !is_null($email) && !empty($email);

		if($validEmail && $isNotEmpty){
			$this->receiverEmail = $email;
		} 
		else{
			if(is_null($email) || empty($email)){
				throw new EmailNotificationException(self::EMPTY_EMAIL);
			}
			else{

				throw new EmailNotificationException(self::INVALID_EMAIL);
			}
			
		}

	}

	private function setSubject($subject){
		
		if(!is_null($subject) && !empty($subject)){
			$this->subject = $subject;
		} 
		else{
			throw new EmailNotificationException(self::EMPTY_SUBJECT);
			
		}

	}

	private function setMessage($message){
		
		if(!is_null($message) && !empty($message)){
			$this->message = $message;
		} 
		else{
			throw new EmailNotificationException(self::EMPTY_MESSAGE);
			
		}

	}

	public function getSenderName(){
		return $this->senderName;
	}

	public function getReceiverName(){
		return $this->receiverName;
	}

	public function getSenderEmail(){
		return $this->senderEmail;
	}

	public function getReceiverEmail(){
		return $this->receiverEmail;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function getMessage(){
		return $this->message;
	}

	public function validEmail($email){
		
		$regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
		$result = preg_match($regex, $email);

		return $result;	
	}
}
