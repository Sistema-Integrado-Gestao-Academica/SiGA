<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("NotificationModel.php");

require_once(APPPATH."/data_types/User.php");

require_once(APPPATH."/data_types/notification/RegularNotification.php");
require_once(APPPATH."/data_types/notification/RegularNotification.php");

require_once(APPPATH."/exception/NotificationException.php");

class NavBarNotification{

	public function sendNotification($notification){

		$model = new NotificationModel();

		$saved = $model->saveNotification($notification);

		return $saved;
	}

	public function getUserNotifications($user){

		$model = new NotificationModel();
		
		$foundNotifications = $model->getUserNotifications($user);
		
		$notifications = array();
		foreach($foundNotifications as $notification){

			$id = $notification[NotificationModel::ID_COLUMN];
			$userId = $notification[NotificationModel::USER_COLUMN];
			$content = $notification[NotificationModel::CONTENT_COLUMN];
			$seen = $notification[NotificationModel::SEEN_COLUMN];
			$link = $notification[NotificationModel::LINK_COLUMN];
			$type = $notification[NotificationModel::TYPE_COLUMN];

			switch($type){
				case RegularNotification::class:
					$notifications[] = new RegularNotification($user, $content, $id, $seen);
					break;
				
				case ActionNotification::class:
					$notifications[] = new ActionNotification($user, $content, $link, $id, $seen);
					break;
				
				default:
					show_error("A tabela de notificações retornou um valor inválido para o tipo de notificação", 500, "Erro no banco de dados.");
					break;
			}
		}

		return $notifications;
	}
}