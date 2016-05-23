<?php

require_once("BarNotification.php");

require_once(MODULESPATH."notification/exception/NotificationException.php");

class ActionNotification extends BarNotification{

	const INVALID_LINK = "O link da notificação informado é inválido. Deve conter um endereço de URL válido.";

	protected $link;

	public function __construct($user, $link, $id = FALSE, $seen = FALSE, $content = FALSE){
		parent::__construct($user, $id, $seen, $content);
		$this->setLink($link);
	}

	private function setLink($link){

		$linkMatchesPattern = preg_match("/^[a-zA-Z0-9_\/~%.:_\-]{1,}$/", $link);

		if($linkMatchesPattern === 1 || $linkMatchesPattern == TRUE){
			$linkMatchesPattern = TRUE;
		}else{
			$linkMatchesPattern = FALSE;
		}

		if(!is_null($link) && !empty($link) && $linkMatchesPattern){
				$this->link = $link;
		}else{
			throw new NotificationException(self::INVALID_LINK);
		}
	}

	public function notify(){
		parent::notify();
	}

	public function link(){
		return $this->link;
	}

	public function type(){
		return self::class;
	}
}