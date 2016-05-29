<?php

require_once ("BarNotificationDecorator.php");

require_once (MODULESPATH."notification/domain/ActionNotification.php");

class OnlineDocumentRequestNotification extends BarNotificationDecorator{

	private $document;

	public function __construct(ActionNotification $notification, $document){
		parent::__construct($notification);
		$this->setDocument($document);		
		$this->message();
	}

	protected function message(){
		
		$message = "O documento <b>".$this->getDocument()."</b> solicitado está pronto. Clique aqui para baixar.";

		$this->notification->setContent($message);
	}

	private function setDocument($document){
		$this->document = $document;
	}

	public function getDocument(){
		return $this->document;
	}

	public function notify(){

		// On notification_helper
		$sent = sendNotification($this->getNotification());

		if(!$sent){
			throw new NotificationException(self::COULDNT_SEND_NOTIFICATION);	
		}
	}

	public function type(){
		return self::class;
	}
}