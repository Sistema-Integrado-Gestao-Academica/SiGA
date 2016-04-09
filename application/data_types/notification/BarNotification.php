<?php

require_once("Notification.php");

require_once(APPPATH."/exception/NotificationException.php");

abstract class BarNotification extends Notification{

	const INVALID_ID = "O ID da notificação deve ser um número maior que zero.";

	private $id;
	protected $content;
	protected $seen;

	public function __construct($user, $content, $id = FALSE, $seen = FALSE){
		parent::__construct($user);
		$this->setId($id);
		$this->setContent($content);
		$this->setSeen($seen);
	}

	private function setId($id){

		if($id !== FALSE){

			// Checks if is a number and greater than 0
			if(!is_nan((double) $id) && $id > 0){
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
			$this->seen = FALSE;
		}
	}

	// Default setter. Can be overrided
	protected function setContent($content){
		$this->content = $content;
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
}