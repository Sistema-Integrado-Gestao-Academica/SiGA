<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends CI_Controller {

	public function checkUserGroup($requiredGroup){

		$groupExists = $this->checkIfGroupExistsByName($requiredGroup);

		if($groupExists){

			$loggedUserData = $this->session->userdata('current_user');
			$userGroups = $loggedUserData['user_groups'];

			$haveGroup = FALSE;
			foreach($userGroups as $group){
				if($group['group_name'] === $requiredGroup){
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
		
		$this->load->model('module_model');
		
		$group = $this->module_model->getGroupById($idGroup);

		return $group;
	}

	public function addGroupToUser($groupName, $userId){

		// Validar o $groupName
		$this->load->model("module_model");

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
		$this->load->model("module_model");

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

		$this->load->model("module_model");

		$group = $this->module_model->getGroupByGroupName($groupName);

		return $group;
	}

	/**
	  * Check the modules registered to an user
	  * @param $user_id - User id to check the modules
	  * @return 
	  */
	public function checkModules($user_id){

		$this->load->model('module_model');
		$registered_modules = $this->module_model->getUserModuleNames($user_id);

		return $registered_modules;
	}
	
	/**
	 * Check existing modules (groups) in the database
	 * @return array with the modules (groups) names
	 */
	public function getExistingModules(){
		
		$this->load->model('module_model');
		$existing_modules = $this->module_model->getAllModules();
		$existing_modules_form = $this->turnCourseTypesToArray($existing_modules);
		
		return $existing_modules_form;
	}

	public function getUserGroups($idUser){
		
		$this->load->model('module_model');

		$groups = $this->module_model->getUserGroups($idUser);

		return $groups;
	}

	public function checkIfGroupExists($idGroup){

		$this->load->model('module_model');

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
