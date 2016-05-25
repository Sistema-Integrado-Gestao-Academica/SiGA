<?php

require_once("BaseNotification.php");
require_once MODULESPATH."notification/constants/EmailConstants.php";
require_once APPPATH."/constants/EmailSenderData.php";
require_once MODULESPATH."notification/exception/EmailNotificationException.php";

abstract class EmailNotification extends BaseNotification{

	// Error messages 
	const EMPTY_NAME = "O nome não pode ser nulo nem vazio.";
	const EMPTY_EMAIL = "O email não pode ser nulo nem vazio.";
	const INVALID_EMAIL = "Email inválido.";
	const EMPTY_SUBJECT = "O assunto não pode ser nulo nem vazio.";
	const EMPTY_MESSAGE = "A mensagem não pode ser nula nem vazia.";

	protected $senderName;
	protected $senderEmail;
	protected $receiverName;
	protected $receiverEmail;
	protected $subject;
	protected $message;

	public function __construct($user){
		parent::__construct($user);
		$this->setSenderName(EmailConstants::SENDER_NAME);
		$this->setSenderEmail(EmailConstants::SENDER_EMAIL);
		$this->setReceiverName($user->getName());
		$this->setReceiverEmail($user->getEmail());
		$this->setSubject();
        $this->setMessage();
	}

	protected abstract function setMessage();
	protected abstract function setSubject();

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

    private function setDefaultConfiguration(){
    	
    	$mail = new PHPMailer();
        $mail->IsSMTP(); 
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl"; 
        $mail->Port = 465; 
        $mail->Host = EmailSenderData::HOST;
        $mail->Username = EmailSenderData::USER;
        $mail->Password = EmailSenderData::PASSWORD;
        $mail->CharSet = 'UTF-8';
    	
    	return $mail;
    }

	public function notify(){
        $emailSent = FALSE;

		$message = $this->getMessage();

		if(!is_null($message) && !empty($message)){

	        $ci =& get_instance();
	        $ci->load->library("Mailing");
	        
	        $mail = $this->setDefaultConfiguration(); 

	        $mail->IsHTML(true);
	        $mail->Subject = $this->getSubject(); 
	        $mail->Body = $message;
	        $mail->SetFrom($this->getSenderEmail(), $this->getSenderName()); 
	        $mail->AddAddress($this->getReceiverEmail(), $this->getReceiverName());
	        
	        $emailSent = $mail->Send();
		}
		else{
			$emailSent = FALSE;
		}
        
        return $emailSent;
	}

}
