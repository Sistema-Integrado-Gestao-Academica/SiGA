<?php

require_once("Notification.php");

abstract class BarNotification extends Notification{

	protected $content;
	protected $seen;

	public function __construct($content, $seen = FALSE, $id = FALSE){
		parent::__construct($id);
		$this->setContent($content);
		$this->setSeen($seen);
	}

	protected function setSeen($seen){

		if(is_bool($seen)){
			$this->seen = $seen;
		}else{
			$this->seen = FALSE;
		}
	}

	protected function setContent($content){
		$this->content = $content;
	}

	public function content(){
		return $this->content;
	}

	public function seen(){
		return $this->seen;
	}
}