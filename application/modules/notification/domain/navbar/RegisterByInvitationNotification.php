<?php

require_once ("BarNotificationDecorator.php");

require_once (MODULESPATH."notification/domain/RegularNotification.php");

class RegisterByInvitationNotification extends BarNotificationDecorator{

	private $invitation;
	private $invitedName;

	public function __construct(RegularNotification $notification, $invitation, $invitedName){
		parent::__construct($notification);
		$this->invitation = $invitation;
		$this->invitedName = $invitedName;
		$this->message();
	}

	protected function message(){
		
		$invitedName = $this->invitedName;
		$invitedGroup = $this->invitation["invited_group"];
		$userName = $this->user()->getName();

		$message = "{$userName}, o usuário <b><i>{$invitedName}</i></b> se cadastrou como <b>{$invitedGroup}</b> no sistema pelo seu convite. Lembre-se de vinculá-lo(a) ao que for necessário.";

		$this->notification->setContent($message);
	}

	public function notify(){

		// On notification_helper
		$sent = sendNotification($this->getNotification());

		if(!$sent){
			throw new NotificationException(self::COULDNT_SEND_NOTIFICATION);	
		}
	}

	public function type(){
		return get_class($this);
	}
}