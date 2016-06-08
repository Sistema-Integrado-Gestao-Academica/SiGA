<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");

class Trainee extends MX_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function getTraineeGroup($secretaryId){
		$this->load->model("auth/module_model");
		$traineeGroupExists = $this->module_model->getGroupBySecretaryId($secretaryId);
		if($traineeGroupExists !== FALSE){
			$traineeGroupId = $traineeGroupExists[0]['id_group'];
		}
		else{
			$traineeGroupId = $this->createNewGroup($secretaryId);

		}
		return $traineeGroupId;
	}

	public function createNewGroup($secretaryId){

		$groupId = GroupConstants::TRAINEE_GROUP_DEFAULT_ID;
		$this->load->model("auth/module_model");
		$success = $this->module_model->addNewGroupOfTrainee($secretaryId);

		if($success){
			$createdGroup = $this->module_model->getGroupBySecretaryId($secretaryId);

			if($createdGroup !== FALSE){
				$groupId = $createdGroup[0]['id_group'];

				$this->load->model("auth/permissions_model");
				$this->permissions_model->addDefaultTraineePermissions($groupId);
			}
		}

		return $groupId;
	}
}
