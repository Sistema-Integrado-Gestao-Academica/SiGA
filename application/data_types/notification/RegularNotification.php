<?php

require_once("BarNotification.php");

class RegularNotification extends BarNotification{

	public function __construct($user, $content, $id = FALSE, $seen = FALSE){
		parent::__construct($user, $content, $id, $seen);
	}

	protected function setContent($content){
		$this->content = $content;
	}

	public function notify(){

	}
}