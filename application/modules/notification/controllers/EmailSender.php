<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."auth/controllers/Useractivation.php");
require_once(MODULESPATH."notification/domain/emails/ConfirmSignUpEmail.php");
require_once(MODULESPATH."notification/domain/emails/UserInvitationEmail.php");
require_once(MODULESPATH."notification/domain/emails/GroupInvitationEmail.php");
require_once(MODULESPATH."notification/domain/emails/GenericEmail.php");

/**
 * Facade class to receive all email notifications request
 */
class EmailSender extends MX_Controller{

	/**
	 * Send a sign up confirmation email to the given user. Needs the user id, name and email.
	 * @param $user - The user to send the email
	 * @param $activation - The activation key to send on the email
	 * @return an associative array with keys 'status' and 'message' with the result of the operation
	 */
	function sendConfirmationEmail($user, $activation){

		$id = $user['id'];
		$name = $user['name'];
		$userEmail = $user['email'];
		$user = new User($id, $name, FALSE, $userEmail);

		$email = new ConfirmSignUpEmail($user, $activation);

		$sent = $email->notify();

		$message = array();
		if($sent){
			$message['status'] = "success";
			$message['message'] = "{$name}, um email foi enviado, para {$userEmail}, para você confirmar seu cadastro no sistema.";
		}
		else{
			$message['status'] = "danger";
			$message['message'] = "{$name}, não foi possível enviar o email para você confirmar seu cadastro no sistema. Cheque o email informado e tente novamente.";
		}

		return $message;
	}

	/**
	 * Send an invitation email to the given email in $invitationData
	 * @param $invitationData - The invitation data with 'secretary ID', 'invited group', 'invited email' and 'invitation number'.
	 */
	public function sendUserInvitationEmail($invitation, $registeredUserInvitation=FALSE){

		$this->load->model("secretary/userInvitation_model");
		$secretaryId = $invitation[UserInvitation_model::SECRETARY_COLUMN];
		$invitedEmail = $invitation[UserInvitation_model::INVITED_EMAIL_COLUMN];
		$invitedGroup = $invitation[UserInvitation_model::INVITED_GROUP_COLUMN];
		$invitationNumber = $invitation[UserInvitation_model::ID_COLUMN];

		$this->load->model("auth/module_model");
		$groupData = $this->module_model->getGroupById($invitedGroup);
		$groupName = $groupData['group_name'];

		// Get the secretary name who has invited
		$this->load->model("auth/usuarios_model");
		$secretary = $this->usuarios_model->getUserById($secretaryId);
		$secretaryName = $secretary["name"];

		// Arbitrary id
		$id = 23423;
		$userEmail = $invitedEmail;
		// Arbitrary name, just to create the User object
		$name = "Arbitrary name";

		$user = new User($id, $name, FALSE, $userEmail);

		if($registeredUserInvitation){
			$email = new GroupInvitationEmail($user, $invitationNumber, $secretaryName, $groupName);
		}
		else{
			$email = new UserInvitationEmail($user, $invitationNumber, $secretaryName);
		}

		$sent = $email->notify();

		return $sent;
	}

	public function sendProjectCoordinatorInvitationEmail($sender, $project, $member, $activation){

		$this->load->model("auth/usuarios_model");
		$userToInvite = $this->usuarios_model->getUserById($member);

		$this->load->model("auth/project_model");
		$project = $this->project_model->getProject($project);

		$params = array(
			'subject' => "Convite para coordenador de projeto",
			'sender' => $sender,
			'project' => $project,
			'activation' => $activation
		);

		// Creating user object
		$userId = $userToInvite['id'];
		$userName = $userToInvite['name'];
		$userEmail = $userToInvite['email'];
		$user = new User($userId, $userName, FALSE, $userEmail);

		$email = new GenericEmail($user, $params, function($params){
			$user = $params['user'];
			$userName = $user->getName();
        	$userId = $user->getId();

			$sender = $params['sender'];
			$senderName = $sender->getName();

			$project = $params['project'];
			$projectName = $project['name'];

			$message = "Olá <b>{$userName}</b>! Você foi convidado(a) pelo(a) <b>{$senderName}</b> para ser coordenador(a) do projeto '<b>{$projectName}</b>'. Para aceitar o convite e se tornar coordenador(a) deste projeto accesse o link abaixo: <br><br>";

			$url = site_url("accept_coordinator_invitation");
			$activation = $params['activation'];
			$link = $url."?k={$activation}";

			$message .= "<a href={$link}>Aceitar convite para ser coordenador do projeto '{$projectName}'</a>";

			return $message;
		});

		$sent = $email->notify();

		return $sent;
	}
}