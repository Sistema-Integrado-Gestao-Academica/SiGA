<?php

require_once(APPPATH."/exception/NotificationException.php");
require_once(APPPATH."/data_types/User.php");

abstract class Notification{

	const INVALID_USER = "Usuário da notificação inválido. Uma notificação deve ser enviada para um usuário do sistema.";

	protected $user;

	public function __construct($user){
		$this->setUser($user);
	}

	public function attachUser($user){
		$this->setUser($user);
	}

	private function setUser($user){
		if(!is_null($user) && is_object($user)){
			if(get_class($user) === User::class){
				$this->user = $user;
			}else{
				throw new NotificationException(self::INVALID_USER);
			}
		}else{
			throw new NotificationException(self::INVALID_USER);
		}
	}

	public function user(){
		return $this->user;
	}

	// Method to notify the notification user
	public abstract function notify();
}