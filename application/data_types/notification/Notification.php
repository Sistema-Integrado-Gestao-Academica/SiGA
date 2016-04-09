<?php

require_once(APPPATH."/exception/NotificationException.php");

abstract class Notification{
	
	const INVALID_ID = "O ID da notificação deve ser um número maior que zero.";

	private $id;
	protected $user;

	public function __construct($user = FALSE, $id = FALSE){
		$this->setId($id);
		$this->setUser($user);
	}

	public function attachUser($user){

		$this->setUser($user);
	}

	private function setId($id){

		if($id !== FALSE){

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

	private function setUser($user){
		if($user !== FALSE){
			$this->user = $user;
		}
	}

	public function id(){
		return $this->id;
	}

	public function user(){
		return $this->user;
	}

	public abstract function notify();
}