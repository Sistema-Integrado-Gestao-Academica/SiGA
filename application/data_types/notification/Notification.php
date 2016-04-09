<?php

require_once(APPPATH."/exception/NotificationException.php");
require_once(APPPATH."/data_types/User.php");

abstract class Notification{

	protected $user;

	public function __construct($user){
		$this->setUser($user);
	}

	public function attachUser($user){

		$this->setUser($user);
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

	public function user(){
		return $this->user;
	}

	// Method to notify the notification user
	public abstract function notify();
}