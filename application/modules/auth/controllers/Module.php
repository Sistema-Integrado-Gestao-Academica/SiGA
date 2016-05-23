<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."auth/domain/Group.php");

class Module extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("auth/module_model");
	}

	public function checkUserGroup($requiredGroup){

		$groupExists = $this->checkIfGroupExistsByName($requiredGroup);

		if($groupExists){

			$session = getSession();
			$userGroups = $session->getUserGroups();
			foreach($userGroups as $group){
				$haveGroup = FALSE;
				if($group->getName() === $requiredGroup){
					$haveGroup = TRUE;
					break;
				}
			}
		}else{
			$haveGroup = FALSE;
		}

		return $haveGroup;
	}

	private function checkIfGroupExistsByName($group){
		$allGroups = $this->getExistingModules();

		$groupExists = FALSE;
		foreach($allGroups as $idGroup => $groupName){
			if($groupName === $group){
				$groupExists = TRUE;
				break;
			}
		}

		return $groupExists;
	}

	public function getGroupById($idGroup){

		$group = $this->module_model->getGroupById($idGroup);

		return $group;
	}

	public function addGroupToUser($groupName, $userId){

		// Validar o $groupName
		
		$group = $this->module_model->getGroupByGroupName($groupName);

		if($group !== FALSE){
			$groupId = $group['id_group'];
		}else{
			$groupId = FALSE;
		}

		$groupToUser = array(
			'id_user' => $userId,
			'id_group' => $groupId
		);

		$this->module_model->addGroupToUser($groupToUser);
	}

	public function deleteGroupOfUser($groupName, $userId){

		// Validar o $groupName
		
		$group = $this->module_model->getGroupByGroupName($groupName);

		if($group !== FALSE){
			$groupId = $group['id_group'];
		}else{
			$groupId = FALSE;
		}

		$groupToUser = array(
			'id_user' => $userId,
			'id_group' => $groupId
		);
		
		$this->module_model->deleteGroupOfUser($groupToUser);
	}

	public function getGroupByName($groupName){

		$group = $this->module_model->getGroupByGroupName($groupName);

		return $group;
	}

	/**
	  * Check the modules registered to an user
	  * @param $user_id - User id to check the modules
	  * @return
	  */
	public function checkModules($user_id){

		$registered_modules = $this->module_model->getUserModuleNames($user_id);

		return $registered_modules;
	}

	/**
	 * Check existing modules (groups) in the database
	 * @return array with the modules (groups) names
	 */
	public function getExistingModules(){

		$modules = $this->module_model->getAllModules();

		foreach ($modules as $module){
			$modules[$module['id_group']] = $module['group_name'];
		}

		return $modules;
	}

	public function getUserGroups($idUser){

		$groups = $this->module_model->getUserGroups($idUser);

		return $groups;
	}

	public function checkIfGroupExists($idGroup){
		
		$groupExists = $this->module_model->checkIfGroupExists($idUser);

		return $groupExists;
	}
}
