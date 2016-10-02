<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."notification/domain/RegularNotification.php");
require_once(MODULESPATH."notification/domain/ActionNotification.php");
require_once(MODULESPATH."notification/domain/navbar/DocumentRequestNotification.php");
require_once(MODULESPATH."notification/domain/navbar/OnlineDocumentRequestNotification.php");
require_once(MODULESPATH."notification/domain/navbar/RegisterByInvitationNotification.php");
require_once(MODULESPATH."notification/domain/emails/GenericEmail.php");
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
	 * Sends a notification to an user of the system. The notification is send, by default,
	 * via email and to the user notification bar. The notification by email can be supressed by the flag $onlyBar, that makes us to send the notification only as a bar notification
	 * @param $receiver - The user that will receive the notification.
	 *		  Can be an array containing at least the keys 'id', 'name' and 'email',
	 *		  or a User object containing the same data.
	 * @param $params - An array containing the data needed by the closure $handler.
	 *		  If $params is a string, it will be treated as the message to send,
	 *		  and $handler will not be called.
	 *		  If $params containg a key called 'link', the notification on the bar
	 *		  will be a link to the URL present on $params['ĺink'], so that the notification will be clickable.
	 * @param $handler - A closure to handle $params and return the message to send.
	 *		  It should return a string.
	 * @param $sender - The user who is sending the notification.
	 *		  The data can come the same way as $receiver (array or User object).
	 *		  If set to FALSE, the logged user will be used.
	 * @param $onlyBar - Flag to determine whether is to send the notification only via bar or not.
	 * @return an array with 2 positions only containing booleans. The first position signals if
	 * 		   the bar notification was sent and the second signal if the email was sent
	 */
	public function notifyUser($receiver, $params=array(), $handler=FALSE, $sender=FALSE, $onlyBar=FALSE){

		if(is_array($receiver)){
			// If is an array it should have at least the keys 'id', 'name' and 'email'
			$receiver = new User($receiver['id'], $receiver['name'], $cpf=FALSE, $receiver['email']);
		}

		if($sender === FALSE){
			// If a specific sender is not specified, use the logged one.
			$sender = getSession()->getUserData();
		}
		elseif(is_array($sender)){
			$sender = new User($sender['id'], $sender['name'], $cpf=FALSE, $sender['email']);
		}

		$senderName = $sender->getName();
		$message = "<i>Mensagem de <b>{$senderName}</b></i>: <br>";

		if(is_string($params)){
			// If is a string, $params is the message to send
			$message .= $params;
		}else{
			$message .= $handler($params);
		}

		if(is_array($params) && array_key_exists('link', $params)){
			// If there is a link on $params, send the notification as an ActionNotification, to be clickable
			$notification = $this->newActionNotification($receiver, $params['link'], $id = FALSE, $seen = FALSE, $message);
		}else{
			$notification = $this->newRegularNotification($receiver, $id = FALSE, $seen = FALSE, $message);
		}

		try{
			// Notify in the notification bar
			$notification->notify();
			$barNotificationSent = TRUE;
		}catch(NotificationException $e){
			$barNotificationSent = FALSE;
		}

		if(!$onlyBar){

			if(is_string($params)){
				$params = array('subject' => "Mensagem de usuário");
			}

			if(is_array($params) && !array_key_exists('subject', $params)){
				$params['subject'] = "Mensagem de usuário";
			}

			// If not only bar notification, send email too
			$email = new GenericEmail($receiver, $params, function($params) use ($message) {
				return $message;
			});

			// Notify via email
			$emailSent = $email->notify();
		}else{
			$emailSent = FALSE;
		}

		return array($barNotificationSent, $emailSent);
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
					if(!array_key_exists($userId, $alreadyNotified)){

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

	public function newRegisterByInvitationNotification($invitationNumber, $invitedName){

		// Get the invitation data
		$this->load->model("secretary/userInvitation_model", "invitation_model");
		$invitation = $this->invitation_model->getInvitation($invitationNumber);

		// Get the group that the user was invited to
		$this->load->model("auth/module_model");
		$invitedGroup = $invitation[UserInvitation_model::INVITED_GROUP_COLUMN];
		$invitedGroup = $this->module_model->getGroupById($invitedGroup);
		$invitedGroupName = ucfirst($invitedGroup["group_name"]);
		$invitation["invited_group"] = $invitedGroupName;

		// Get the secretary who invited data
		$this->load->model("auth/usuarios_model");
		$secretaryId = $invitation[UserInvitation_model::SECRETARY_COLUMN];
		$secretary = $this->usuarios_model->getUserById($secretaryId);

		// Creating a user object to the notification
		$user = new User($secretaryId, $secretary["name"], '', $secretary["email"]);

		$notification = $this->newRegularNotification($user);
		$notification = new RegisterByInvitationNotification($notification, $invitation, $invitedName);
		$notification->notify();
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
					case "DocumentRequestNotification":
					case "RegularNotification":
						$notifications[] = new RegularNotification($user, $id, $seen, $content);
						break;

					case "OnlineDocumentRequestNotification":
					case "ActionNotification":
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