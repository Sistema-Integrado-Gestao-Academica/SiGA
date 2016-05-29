<?php

require_once MODULESPATH."notification/domain/BarNotification.php";
require_once MODULESPATH."notification/domain/ActionNotification.php";

abstract class BarNotificationDecorator extends BarNotification{
	
	protected $notification;

	public function __construct(BarNotification $notification){
		$this->notification = $notification;

		$user = $notification->user();
		$id = $notification->id();
		$seen = $notification->seen();
		$content = $notification->content();

		parent::__construct($user, $id, $seen, $content);
	}

	protected abstract function message();

	protected function getNotification(){
		return $this->notification;
	}

	// Override
	public function type(){
		return self::class;
	}
}