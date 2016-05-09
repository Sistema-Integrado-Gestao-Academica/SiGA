<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("NotificationModel.php");

require_once(APPPATH."/controllers/Course.php");
require_once(APPPATH."/controllers/Usuario.php");

require_once(APPPATH."/data_types/User.php");

require_once(APPPATH."/data_types/notification/RegularNotification.php");
require_once(APPPATH."/data_types/notification/ActionNotification.php");

require_once(APPPATH."/data_types/notification/navbar/DocumentRequestNotification.php");

require_once(APPPATH."/exception/NotificationException.php");

/**
 * Facade class to receive all NavBar notifications request
 */
class NavBarNotification{

	/**
	 * Notify the secretaries of the given course that the request student requested a document
	 * @param $requestStudent - The student that requested the requestDoc document
	 * @param $courseToNotify - The course to notify all secretaries
	 * @param $requestedDoc - The document type that the student requested
	 */
	public function documentRequestNotification($requestStudent, $courseToNotify, $requestedDoc){


		// Get request student name
		$user = new Usuario();
		$student = $user->getUserById($requestStudent);
		$studentName = $student["name"];
		

		// Get all secretaries of the course
		$course = new Course();
		$secretaries = $course->getCourseSecretaries($courseToNotify);

		if($secretaries !== FALSE){

			foreach($secretaries as $secretary){


				try{
					$userId = $secretary["id_user"];

					// The secretary name does not matter to the notification, this is a arbitrary name 
					$user = new User($userId, "secretaryname");

					$notification = $this->newRegularNotification($user);
					$notification = new DocumentRequestNotification($notification, $requestedDoc, $studentName);
					$notification->notify();

				}catch(NotificationException $e){
					throw $e;
				}
			}
		}
	}

	/**
	 * Creates a new regular notification 
	 * @throws NotificationException in case of fail of instantiation
	 */
	private function newRegularNotification($user, $id = FALSE, $seen = FALSE, $content = FALSE){

		$notification = new RegularNotification($user, $id, $seen, $content);

		return $notification;
	}

	/**
	 * Creates a new action notification 
	 * @throws NotificationException in case of fail of instantiation
	 */
	private function newActionNotification($user, $link, $id = FALSE, $seen = FALSE, $content = FALSE){

		$notification = new ActionNotification($user, $link, $id, $seen, $content);

		return $notification;
	}

	public function sendNotification($notification){

		$model = new NotificationModel();

		$saved = $model->saveNotification($notification);

		return $saved;
	}

	public function setNotificationSeen($notificationId){

		$model = new NotificationModel();

		$updated = $model->setNotificationSeen($notificationId);

		return $updated;
	}

	public function getUserNotifications($user){

		$model = new NotificationModel();
		
		$foundNotifications = $model->getUserNotifications($user);
		
		$notifications = array();
		$notSeenNotifications = 0;
		if($foundNotifications !== FALSE){

			foreach($foundNotifications as $notification){

				$id = $notification[NotificationModel::ID_COLUMN];
				$userId = $notification[NotificationModel::USER_COLUMN];
				$content = $notification[NotificationModel::CONTENT_COLUMN];
				$seen = $notification[NotificationModel::SEEN_COLUMN];
				$link = $notification[NotificationModel::LINK_COLUMN];
				$type = $notification[NotificationModel::TYPE_COLUMN];

				switch($type){
					case DocumentRequestNotification::class:
					case RegularNotification::class:
						$notifications[] = new RegularNotification($user, $id, $seen, $content);
						break;
					
					case ActionNotification::class:
						$notifications[] = new ActionNotification($user, $link, $id, $seen, $content);
						break;

					/**
						Colocar a classe de decorator como case
					 */
					default:
						show_error("A tabela de notificações retornou um valor inválido para o tipo de notificação", 500, "Erro no banco de dados.");
						break;
				}

				if($seen === "0" || $seen === 0 || $seen === FALSE){
					$notSeenNotifications++;
				}
			}
		}

		$result["not_seen"] = $notSeenNotifications;
		$result["notifications"] = $notifications;

		return $result;
	}
}