<?php

require_once(MODULESPATH."notification/controllers/Notification.php");
require_once(MODULESPATH."notification/domain/BaseNotification.php");

function getUserNotifications(){
	
	$notification = new Notification();

	$session = getSession();
    $user = $session->getUserData();

	$notifications = $notification->getUserNotifications($user);

	return $notifications;
}

function sendNotification(BaseNotification $notificationToSend){

	$notification = new Notification();

	$sent = $notification->sendNotification($notificationToSend);
	
	return $sent;	
}