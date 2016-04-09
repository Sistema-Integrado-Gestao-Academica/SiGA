<?php

require_once("BarNotification.php");

class ActionNotification extends BarNotification{

	public function __construct($user, $content, $id = FALSE, $seen = FALSE){
		parent::__construct($user, $content, $id, $seen = FALSE);
	}

	protected function setContent($content){
		$this->content = $content;
	}

	public function notify(){

	}

}