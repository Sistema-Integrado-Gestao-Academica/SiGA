<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."notification/domain/emails/UserInvitationEmail.php");

/**
 * Facade class to receive all email notifications request
 */
class EmailSender extends MX_Controller{

	/**
	 * Send an invitation email to the given email in $invitationData
	 * @param $invitationData - The invitation data with 'secretary ID', 'invited group', 'invited email' and 'invitation number'.
	 */
	public function sendUserInvitationEmail($invitation){

		$this->load->model("secretary/userInvitation_model");
		$secretaryId = $invitation[UserInvitation_model::SECRETARY_COLUMN];
		$invitedEmail = $invitation[UserInvitation_model::INVITED_EMAIL_COLUMN];
		$invitationNumber = $invitation[UserInvitation_model::ID_COLUMN];

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

		$email = new UserInvitationEmail($user, $invitationNumber, $secretaryName);

		$sent = $email->notify();

		return $sent;
	}
}