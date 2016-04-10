<?php

require_once("BarNotification.php");

require_once(APPPATH."/exception/NotificationException.php");

class ActionNotification extends BarNotification{

	const INVALID_LINK = "O link da notificação informado é inválido. Deve conter um endereço de URL válido.";

	protected $link;

	public function __construct($user, $content, $link, $id = FALSE, $seen = FALSE){
		parent::__construct($user, $content, $id, $seen = FALSE);
		$this->setLink($link);
	}

	private function setLink($link){

		// $linkMatchesPattern = preg_match("", $link);

		if(!is_null($link) && !empty($link)){
			$this->link = $link;
		}else{
			throw new NotificationException(self::INVALID_LINK);
		}
	}

	public function link(){
		return $this->link;
	}

	public function notify(){

	}

}