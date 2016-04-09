<?php

require_once APPPATH."/exception/EmailNotificationException.php";
require_once("Notification.php");

class EmailNotification extends Notification{

	// Error messages 
	const EMPTY_NAME = "O nome não pode ser nulo nem vazio.";
	const EMPTY_EMAIL = "O email não pode ser nulo nem vazio.";
	const INVALID_EMAIL = "Email inválido.";
	const EMPTY_SUBJECT = "O assunto não pode ser nulo nem vazio.";
	const EMPTY_MESSAGE = "A mensagem não pode ser nula nem vazia.";

	// Default values
	const SENDER_NAME = "UNB";
    const SENDER_EMAIL = "unb@unb.br";

	private $senderName;
	private $senderEmail;
	private $receiverName;
	private $receiverEmail;
	private $subject;
	private $message;


	public function __construct($user, $receiverName, $receiverEmail, $subject, $message){
		parent::__construct($user);
		$this->setSenderName(self::SENDER_NAME);
		$this->setSenderEmail(self::SENDER_EMAIL);
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

	public function notify(){

	}

	
	/**
        * Send a email for a user
        * @param $userEmail: The email address of the user
        * @param $instituteName: The name of the institute
        * @param $instituteEmail: The email address of the institute
        * @param $subject: The subject of the email
        * @param $message: The message of the email
    */
    private function sendEmailForUser($email){

        $emailSent = FALSE;
        $this->load->library("My_PHPMailer");
        $mail = $this->setDefaultConfiguration(); 
        $mail->IsHTML(true);
        $mail->Subject = $email->getSubject(); 
        $mail->Body = $email->getMessage();
        $mail->SetFrom($email->getSenderName(), $email->getSenderEmail()); 
        $mail->AddAddress($email->getReceiverName(), $email->getReceiverEmail());
        $emailSent = $mail->Send();

        return $emailSent;
    }

}
