<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."notification/domain/RegularNotification.php");
require_once(MODULESPATH."notification/domain/ActionNotification.php");
require_once(MODULESPATH."notification/domain/navbar/DocumentRequestNotification.php");
require_once(MODULESPATH."notification/domain/navbar/OnlineDocumentRequestNotification.php");
require_once(MODULESPATH."notification/exception/NotificationException.php");

/**
 * Facade class to receive all NavBar notifications request
 */
class Notification extends MX_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model("notification/notification_model", "model");
	}

	/**
	 * Notify the secretaries of the given course that the request student requested a document
	 * @param $requestStudent - The student that requested the requestDoc document
	 * @param $courseToNotify - The course to notify all secretaries
	 * @param $requestedDoc - The document type that the student requested
	 */
	public function documentRequestNotification($requestStudent, $courseToNotify, $requestedDoc){

		// Get request student name
		$this->load->model("auth/usuarios_model");
		$student = $this->usuarios_model->getUserById($requestStudent);
		$studentName = $student["name"];
		
		// Get all secretaries of the course
		$this->load->model("program/course_model");
		$secretaries = $this->course_model->getCourseSecretaries($courseToNotify);

		if($secretaries !== FALSE){

			$alreadyNotified = array();

			foreach($secretaries as $secretary){

				try{
					$userId = $secretary["id_user"];
					
					if(!$alreadyNotified[$userId]){

						// The secretary name does not matter to the notification, this is a arbitrary name 
						$user = new User($userId, "secretaryname");

						$notification = $this->newRegularNotification($user);
						$notification = new DocumentRequestNotification($notification, $requestedDoc, $studentName);
						$notification->notify();

						$alreadyNotified[$userId] = TRUE;
					}
				}catch(NotificationException $e){
					throw $e;
				}
			}
		}
	}

	public function secretaryOnlineDocRequestNotification($requestId){

		$this->load->model("secretary/documentrequest_model");
		$request = $this->documentrequest_model->getDocRequestById($requestId);

		if($request !== FALSE){
			$type = $this->documentrequest_model->getDocumentType($request['document_type']);
			$type = $type["document_type"];

			// Get request student
			$this->load->model("auth/usuarios_model");
			$student = $this->usuarios_model->getUserById($request['id_student']);

			try{
				$userId = $student["id"];
				$userName = $student["name"];

				$user = new User($userId, $userName);
				$link = "download_doc/".$requestId;

				$notification = $this->newActionNotification($user, $link);
				$notification = new OnlineDocumentRequestNotification($notification, $type);
				$notification->notify();

			}catch(NotificationException $e){
				throw $e;
			}
		}else{
			// Do not create the notification because the request does not exists
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

		$saved = $this->model->saveNotification($notification);

		return $saved;
	}

	public function setNotificationSeen($notificationId){

		$updated = $this->model->setNotificationSeen($notificationId);

		return $updated;
	}

	public function getUserNotifications($user){
		
		$foundNotifications = $this->model->getUserNotifications($user);
		
		$notifications = array();
		$notSeenNotifications = 0;
		if($foundNotifications !== FALSE){

			foreach($foundNotifications as $notification){

				$id = $notification[Notification_model::ID_COLUMN];
				$userId = $notification[Notification_model::USER_COLUMN];
				$content = $notification[Notification_model::CONTENT_COLUMN];
				$seen = $notification[Notification_model::SEEN_COLUMN];
				$link = $notification[Notification_model::LINK_COLUMN];
				$type = $notification[Notification_model::TYPE_COLUMN];

				switch($type){
					case DocumentRequestNotification::class:
					case RegularNotification::class:
						$notifications[] = new RegularNotification($user, $id, $seen, $content);
						break;
					
					case OnlineDocumentRequestNotification::class:
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