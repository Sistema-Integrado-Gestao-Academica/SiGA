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
			GroupConstants::TEACHER_GROUP_ID => "Docente"
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

		$emailIsOk = $this->validateInvitationEmail();

		if($emailIsOk){

			$confirmed = $this->input->post("send_invitation_email_confirm");
			if($confirmed){
				$confirmed = TRUE;	
			}
			$emails = $this->input->post("emails_to_invite");
        	$emails = explode(";", $emails);

			$emailNotExists = $this->invitationEmailsExists($emails);

			if($emailNotExists || (!$emailNotExists && $confirmed)){
				$session = getSession();
				$user = $session->getUserData();
				$secretaryName = $user->getName();

				$secretaryId = $user->getId();
				$invitationGroup = $this->input->post("invitation_profiles");
				$invitationStatus = $this->inviteEachUser($emails, $invitationGroup, $secretaryId, $confirmed);
				
				$status = "";
				$message = "";
						
				if(!empty($invitationStatus)){
					$someEmailNotSent = in_array(FALSE, $invitationStatus);
					$someEmailAreSent = in_array(TRUE, $invitationStatus);

					if(!$someEmailNotSent){
						$status = "success";
						$message = "{$secretaryName}, um email foi enviado para todos os emails digitados para se cadastrar no sistema.";
					}
					else if(!$someEmailAreSent){
						$status = "danger";
						$message = "{$secretaryName}, não foi possível enviar os emails para os usuários se cadastrarem no sistema. Cheque os e-mails informados e tente novamente.";
					}
					else{
						$message = "{$secretaryName}, um email foi enviado de convite para cadastro no sistema, apenas para os seguintes emails: ";
						foreach ($invitationStatus as $email => $status) { // The email is the id
							if($status){
								$message .= $email.",";
							}
						}
						$status = "success";
						$message = substr($message, 0, -1);
					}
				}

				$session = getSession();
				$session->showFlashMessage($status, $message);
				redirect("secretary/userInvitation/index");

			}
			else{
				$this->inviteRegisteredUser($emails);
			}
		}else{
			$this->index();
		}
	}

	private function inviteEachUser($emails, $invitationGroup, $secretaryId, $confirmed){

		$invitationStatus = array();

		foreach ($emails as $id => $email) {
			$invitationNumber = $this->generateInvitationNumber();
			$invitationData = array(
				UserInvitation_model::ID_COLUMN => $invitationNumber,
				UserInvitation_model::INVITED_GROUP_COLUMN => $invitationGroup,
				UserInvitation_model::INVITED_EMAIL_COLUMN => $email,
				UserInvitation_model::SECRETARY_COLUMN => $secretaryId,
				UserInvitation_model::ACTIVE_COLUMN => TRUE
			);

			// Send email
			$this->load->module("notification/emailSender");
			$sent = $this->emailsender->sendUserInvitationEmail($invitationData, $confirmed);
			if($sent){
				// Save the sent invitation
				$this->invitation_model->save($invitationData);
			}	

			$invitationStatus[$email] = $sent;
		}

		return $invitationStatus;
	}

	public function inviteRegisteredUser($emails){

		$this->load->model("auth/usuarios_model");
		$this->load->model("auth/module_model");

		// Get the user to be invited'
		$user = $this->usuarios_model->getUserByEmail($email);
		$userGroups = $this->module_model->getUserGroups($user->getId());

		$invitationGroups = array(
			GroupConstants::GUEST_USER_GROUP_ID => "Convidado",
			GroupConstants::TEACHER_GROUP_ID => "Docente"
		);

		// Take out the groups that the user is already enrolled
		foreach ($invitationGroups as $groupId => $group){
			foreach ($userGroups as $group){
				if($groupId == $group['id_group']){
					unset($invitationGroups[$groupId]);
				}
			}
		}

		$data = array(
			"invitationGroups" => $invitationGroups,
			"userToInvite" => $user,
			"userGroups" => $userGroups
		);

		loadTemplateSafelyByPermission(
			PermissionConstants::INVITE_USER_PERMISSION,
			"secretary/userinvitation/registered_user_invitation",
			$data
		);
	}

	public function joinGroup(){

		$invitation = $this->input->get("invitation");

		$session = getSession();

		$foundInvitation = $this->invitation_model->getInvitation($invitation);
		if($foundInvitation !== FALSE){
			// Check if the invitation link was already used
			if($foundInvitation[UserInvitation_model::ACTIVE_COLUMN]){

				$this->load->model("auth/module_model");
				$invitedGroup = $foundInvitation[UserInvitation_model::INVITED_GROUP_COLUMN];
				$invitedGroupData = $this->module_model->getGroupById($invitedGroup);

				if($invitedGroupData !== FALSE){
					$this->load->model("auth/usuarios_model");
					$user = $this->usuarios_model->getUserByEmail($foundInvitation[UserInvitation_model::INVITED_EMAIL_COLUMN]);

					// Add the requested group to the user
					$groupAdded = $this->usuarios_model->addGroupToUser($user->getId(), $invitedGroup);

					// Disable the invitation link
					$this->invitation_model->disable($foundInvitation[UserInvitation_model::ID_COLUMN]);

					if($groupAdded){
						$status = "success";
						$message = "Grupo adicionado com sucesso!";
					}else{
						$status = "danger";
						$message = "Não foi possível adicionar esse grupo para este usuário. Tente novamente ou contate a secretaria do seu curso.";
					}

					$session->showFlashMessage($status, $message);
					redirect("/");
				}else{
					$session->showFlashMessage("danger", "Convite de cadastro defeituoso. Contate o(a) secretário(a) do curso para lhe enviar outro convite.");
					redirect("/");
				}
			}else{
				$session->showFlashMessage("danger", "Convite de cadastro já utilizado. Contate a secretaria do curso para solicitar novo convite.");
				redirect("/");	
			}
		}else{
			$session->showFlashMessage("danger", "Convite de cadastro não confirmado.");
			redirect("/");
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

	private function validateInvitationEmail(){
		$this->load->library("form_validation");
		$emails = $this->input->post("emails_to_invite");
		$this->form_validation->set_rules("emails_to_invite", "E-mail", "required|valid_multiple_emails");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$status = $this->form_validation->run();

		return $status;
	}
	
	private function invitationEmailsExists($emails){

		$this->load->library("form_validation");
		$emailsNotExists = TRUE;

        foreach ($emails as $email) {
            $emailNotExists = $this->form_validation->verify_if_email_no_exists($email);
            if(!$emailNotExists){
                $emailsNotExists = FALSE;
                break;
            }
        }

        return $emailsNotExists;
	}

	

	public function register($userInvitation=""){

		if(empty($userInvitation)){
			$invitation = $this->input->get("invitation");
		}else{
			$invitation = $userInvitation;
		}

		$session = getSession();

		$foundInvitation = $this->invitation_model->getInvitation($invitation);
		if($foundInvitation !== FALSE){
			// Check if the invitation link was already used
			if($foundInvitation[UserInvitation_model::ACTIVE_COLUMN]){

				$this->load->model("auth/module_model");
				$invitedGroup = $foundInvitation[UserInvitation_model::INVITED_GROUP_COLUMN];
				$invitedGroupData = $this->module_model->getGroupById($invitedGroup);

				if($invitedGroupData !== FALSE){

					$groups = array(
						$invitedGroup => ucfirst($invitedGroupData["group_name"])
					);

					$data = array(
						"groups" => $groups,
						"invitedEmail" => $foundInvitation[UserInvitation_model::INVITED_EMAIL_COLUMN],
						"userInvitation" => $invitation
					);

					$this->load->template("secretary/userinvitation/invitation_register", $data);
				}else{
					$session->showFlashMessage("danger", "Convite de cadastro defeituoso. Contate o(a) secretário(a) do curso para lhe enviar outro convite.");
					redirect("/");	
				}
			}else{
				$session->showFlashMessage("danger", "Convite de cadastro já utilizado. Contate a secretaria do curso para solicitar novo convite.");
				redirect("/");	
			}
		}else{
			$session->showFlashMessage("danger", "Convite de cadastro não confirmado.");
			redirect("/");
		}
	}
}
