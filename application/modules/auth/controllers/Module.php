<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."auth/domain/Group.php");
require_once("SessionManager.php");

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

				$existing_modules = $this->module_model->getAllModules();
		$existing_modules_form = $this->turnCourseTypesToArray($existing_modules);

		return $existing_modules_form;
	}

	public function getUserGroups($idUser){

		$groups = $this->module_model->getUserGroups($idUser);

		return $groups;
	}

	public function checkIfGroupExists($idGroup){

		
		$groupExists = $this->module_model->checkIfGroupExists($idUser);

		return $groupExists;
	}

	/**
	 * Join the id's and names of modules (groups) into an array as key => value.
	 * Used to the update course form
	 * @param $modules - The array that contains the tuples of modules
	 * @return An array with the id's and modules names as id => module_name
	 */
	private function turnCourseTypesToArray($modules){
		// Quantity of course types registered
		$quantity_of_course_types = sizeof($modules);

		for($cont = 0; $cont < $quantity_of_course_types; $cont++){
			$keys[$cont] = $modules[$cont]['id_group'];
			$values[$cont] = $modules[$cont]['group_name'];
		}

		$form_modules = array_combine($keys, $values);

		return $form_modules;
	}

}
