<?php

require_once("BarNotification.php");

class RegularNotification extends BarNotification{

	public function __construct($content, $id = FALSE){
		parent::__construct($content, $id);
	}

	protected function setContent($content){

	}

	public function notify(){

	}
}