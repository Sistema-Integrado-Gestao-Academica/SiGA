<?php

require_once("Notification.php");

require_once(APPPATH."/exception/NotificationException.php");

abstract class BarNotification extends Notification{

	const INVALID_ID = "O ID da notificação deve ser um número maior que zero.";
	const INVALID_CONTENT = "Não pode existir uma notificação vazia. Deve conter um texto.";
	const COULDNT_SEND_NOTIFICATION = "Não foi possível salvar a notificação.";

	private $id;
	protected $content;
	protected $seen;
	protected $type;

	public function __construct($user, $content, $id = FALSE, $seen = FALSE){
		parent::__construct($user);
		$this->setId($id);
		$this->setContent($content);
		$this->setSeen($seen);
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

	// Default setter. Can be overrided
	protected function setContent($content){
		if(!is_null($content) && is_string($content) && !empty($content)){
			$this->content = $content;
		}else{
			throw new NotificationException(self::INVALID_CONTENT);
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

		$ci =& get_instance();

		$sent = $ci->navbarnotification->sendNotification($this);

		if(!$sent){
			throw new NotificationException(self::COULDNT_SEND_NOTIFICATION);	
		}
	}
}