<?php

require_once ("BarNotificationDecorator.php");

require_once (MODULESPATH."notification/domain/RegularNotification.php");

class DocumentRequestNotification extends BarNotificationDecorator{
	
	private $requestedDoc;
	private $requestStudent;

	public function __construct(RegularNotification $notification, $requestedDoc, $requestStudent){
		parent::__construct($notification);
		$this->setRequestedDoc($requestedDoc);
		$this->setRequestStudent($requestStudent);
		$this->message();
	}

	protected function message(){
		
		$message = "O(A) aluno(a) <b>".$this->getRequestStudent()
					."</b> solicitou o documento <b>"
					.$this->getRequestedDoc()."</b>.";

		$this->setContent($message);
	}

	private function setRequestedDoc($requestedDoc){

		$this->requestedDoc = $requestedDoc;
	}

	private function setRequestStudent($requestStudent){

		$this->requestStudent = $requestStudent;
	}

	public function getRequestedDoc(){
		return $this->requestedDoc;
	}

	public function getRequestStudent(){
		return $this->requestStudent;
	}

	public function type(){
		return self::class;
	}
}