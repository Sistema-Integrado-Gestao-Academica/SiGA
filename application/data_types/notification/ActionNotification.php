<?php

require_once("BarNotification.php");

class ActionNotification extends BarNotification{

	public function __construct($user, $content, $seen = FALSE, $id = FALSE){
		parent::__construct($user, $content, $seen, $id);
	}

	protected function setContent($content){

	}

	public function notify(){

	}

}