<?php

require_once("BaseNotification.php");
require_once(MODULESPATH."notification/exception/NotificationException.php");

abstract class BarNotification extends BaseNotification{

	const INVALID_ID = "O ID da notificação deve ser um número maior que zero.";
	const INVALID_CONTENT = "Não pode existir uma notificação vazia. Deve conter um texto.";
	const COULDNT_SEND_NOTIFICATION = "Não foi possível salvar a notificação.";

	private $id;
	protected $content;
	protected $seen;
	protected $type;

	public function __construct($user, $id = FALSE, $seen = FALSE, $content = FALSE){
		parent::__construct($user);
		$this->setId($id);
		$this->setSeen($seen);
		$this->setContent($content);
	}

	public abstract function type();

	private function setId($id){

		if($id !== FALSE){

			// Checks if is a number and greater than 0
			if(!is_nan((double) $id) && ctype_digit($id) && $id > 0){
				$this->id = $id;
			}else{
				throw new NotificationException(self::INVALID_ID);
			}
		}else{
			//If the ID is FALSE, is because is a new object, not coming from DB
			$this->id = $id;
		}
	}

	protected function setSeen($seen){
		
		if(is_bool($seen)){
			$this->seen = $seen;
		}else{
			if($seen === "1" || $seen === 1){
				$seen = TRUE;
			}else{
				$seen = FALSE;
			}
			$this->seen = $seen;
		}
	}

	protected function setContent($content){

		if($content !== FALSE){

			if(!is_null($content) && is_string($content) && !empty($content)){
				$this->content = $content;
			}else{
				throw new NotificationException(self::INVALID_CONTENT);
			}
		}else{
			$this->content = $content;
			/**
			 * Nothing to do. When content is not false is because is coming from database,
			 * Otherwise it is automatically generated on decorator classes.
			 */ 
		}
	}

	public function id(){
		return $this->id;
	}

	public function content(){
		return $this->content;
	}

	public function seen(){
		return $this->seen;
	}

	public function notify(){

		// On notification_helper
		$sent = sendNotification($this);

		if(!$sent){
			throw new NotificationException(self::COULDNT_SEND_NOTIFICATION);	
		}
	}
}