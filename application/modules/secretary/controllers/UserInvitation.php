<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");

class UserInvitation extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('secretary/userInvitation_model', "invitation_model");
	}

	public function index(){

		$invitationGroups = array(
			GroupConstants::GUEST_USER_GROUP_ID => "Convidado",
			GroupConstants::TEACHER_GROUP_ID => "Docente",
			GroupConstants::TRAINEE_GROUP_DEFAULT_ID => "Estagiário"
		);

		$data = array(
			"invitationGroups" => $invitationGroups
		);

		loadTemplateSafelyByPermission(
			PermissionConstants::INVITE_USER_PERMISSION,
			"secretary/userinvitation/index",
			$data
		);
	}

	public function invite(){

		$dataIsOk = $this->validateInvitationData();

		if($dataIsOk){

			$session = getSession();
			$user = $session->getUserData();
			$secretaryName = $user->getName();

			$secretaryId = $user->getId();
			$invitationGroup = $this->input->post("invitation_profiles");
			$emailToInvite = $this->input->post("email_to_invite");
			$invitationNumber = $this->generateInvitationNumber();

			// If the choice is a trainee
			if($invitationGroup == GroupConstants::TRAINEE_GROUP_DEFAULT_ID){
				$this->load->module("secretary/trainee");
				$invitationGroup = $this->trainee->getTraineeGroup($secretaryId);
			}

			$invitationData = array(
				UserInvitation_model::ID_COLUMN => $invitationNumber,
				UserInvitation_model::INVITED_GROUP_COLUMN => $invitationGroup,
				UserInvitation_model::INVITED_EMAIL_COLUMN => $emailToInvite,
				UserInvitation_model::SECRETARY_COLUMN => $secretaryId
			);

			// Send email
			$this->load->module("notification/emailSender");
			$sent = $this->emailsender->sendUserInvitationEmail($invitationData);

			if($sent){
				
				// Save the sent invitation
				$this->invitation_model->save($invitationData);

				$status = "success";
				$message = "{$secretaryName}, um email foi enviado para <i><b>{$emailToInvite}</b></i> convidando-o(a) para se cadastrar no sistema.";
			}
			else{
				$status = "danger";
				$message = "{$secretaryName}, não foi possível enviar o email para <i><b>{$emailToInvite}</b></i> convidando-o(a) para se cadastrar no sistema. Cheque o e-mail informado e tente novamente.";
			}

			$session = getSession();
			$session->showFlashMessage($status, $message);
			redirect("secretary/userInvitation/index");

		}else{
			$this->index();
		}
	}

	private function generateInvitationNumber(){
		$alreadyExists = TRUE;
		while($alreadyExists){
			// Generates a cryptographically secure random string as invitation
			$invitation = bin2hex(openssl_random_pseudo_bytes(20));
			$alreadyExists = $this->invitation_model->invitationExists($invitation);
		}

		return $invitation;
	}

	private function validateInvitationData(){

		$this->load->library("form_validation");
		$this->form_validation->set_rules("email_to_invite", "E-mail", "required|valid_email");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$status = $this->form_validation->run();

		return $status;
	}
}
