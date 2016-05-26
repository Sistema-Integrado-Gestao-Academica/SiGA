<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."auth/domain/Group.php");
require_once(MODULESPATH."auth/exception/GroupException.php");

class Module extends MX_Controller {

	const MODEL_NAME = "auth/module_model";

    public function __construct(){
        parent::__construct();
        $this->load->model(self::MODEL_NAME);
    }

    /**
     * Get the groups of an user
     * @param $user - The user to get the groups of
     * @return An array of Group objects or FALSE if none groups is found for the user
     */
    public function loadUserGroups($user){

        $this->load->module("auth/userPermission");

        $foundGroups = $this->module_model->getUserGroups($user);    

        if($foundGroups !== FALSE){

            $groups = array();
            foreach($foundGroups as $foundGroup){
                try{

                    $groupId = $foundGroup['id_group'];
                    $groupName = $foundGroup['group_name'];
                    $groupProfileRoute = $foundGroup['profile_route'];

                    $permissions = $this->userpermission->getGroupPermissions($groupId);

                    $group = new Group($groupId, $groupName, $groupProfileRoute, $permissions);

                    $groups[] = $group;

                }catch(GroupException $e){
                    GroupException::handle($e);
                }
            }
        }else{
            $groups = FALSE;
        }

        return $groups;
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
}
