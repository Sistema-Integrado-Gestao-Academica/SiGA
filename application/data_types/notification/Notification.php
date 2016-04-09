<?php

require_once(APPPATH."/exception/NotificationException.php");
require_once(APPPATH."/data_types/User.php");

abstract class Notification{
	
	const INVALID_ID = "O ID da notificação deve ser um número maior que zero.";

	private $id;
	protected $user;

	public function __construct($user, $id = FALSE){
		$this->setId($id);
		$this->setUser($user);
	}

	public function attachUser($user){

		$this->setUser($user);
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

	private function setUser($user){
		if($user !== FALSE){
			if(is_object($user) && !is_null($user)){
				if(get_class($user) === User::class){
					$this->user = $user;
				}else{
					throw new NotificationException(self::INVALID_USER);
				}
			}else{
				throw new NotificationException(self::INVALID_USER);
			}
		}else{
			// Nothing to do, because the user can be attached later
		}
	}

	public function id(){
		return $this->id;
	}

	public function user(){
		return $this->user;
	}

	// Method to notify the notification user
	public abstract function notify();
}