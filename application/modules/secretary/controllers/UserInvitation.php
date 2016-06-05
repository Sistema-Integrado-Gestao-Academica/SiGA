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
		
	}
}
