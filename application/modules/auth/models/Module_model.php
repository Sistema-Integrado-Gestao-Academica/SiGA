<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends CI_Model {

	public function addGroupToUser($groupToUser){

		$this->db->insert('user_group', $groupToUser);
	}
	
	public function deleteGroupOfUser($userGroup){

		$this->db->delete('user_group', $userGroup);
	}

	/**
	  * Search on database for the permissions of an user
	  * @param $userId - User id to look for permissions
	  * @return an array with the permissions names and routes of the given user
	  */
	public function getUserPermissions($userId){

		$this->load->model("permission_model");

		$groups = $this->getUserModules($userId);

		$userPermissions = array();
		foreach($groups as $group){
			
			$groupId = $group['id_group'];
			$groupPermissions = $this->permission_model->getGroupPermissions($groupId);

			$userPermissions[$group['group_name']] = $groupPermissions;
		}
	
		$userPermissions = checkArray($userPermissions);
		
		return $userPermissions;
	}

	public function getUserGroups($userId){
        $this->db->select('group.*');
        $this->db->from('group');
        $this->db->join('user_group', 'group.id_group = user_group.id_group');
        $this->db->where('user_group.id_user', $userId);

        $foundGroups = $this->db->get()->result_array();

        $foundGroups = checkArray($foundGroups);

        return $foundGroups;
    }

	public function getGroupById($idGroup){

		$searchResult = $this->db->get_where('group', array('id_group' => $idGroup));

		$foundGroup = $searchResult->row_array();

		$foundGroup = checkArray($foundGroup);

		return $foundGroup;
	}
	
	public function getGroupByGroupName($groupName){
		
		$searchResult = $this->db->get_where("group", array('group_name' => $groupName));

		$foundGroup = $searchResult->row_array();

		$foundGroup = checkArray($foundGroup);

		return $foundGroup;
	}

	public function getGroupIdByName($groupsNames){
		$academicGroupId = $this->db->get_where('group',array('group_name'=>$groupsNames['academic']))->row_array();
		$financialGroupId = $this->db->get_where('group',array('group_name'=>$groupsNames['financial']))->row_array();
		
		$academicGroupId = checkArray($academicGroupId);
		$financialGroupId = checkArray($financialGroupId);
		
		$groupsIds = array('academic'=>$academicGroupId['id_group'], 
						   'financial'=>$financialGroupId['id_group']);
		return $groupsIds;
	}
	
	/**
	  * Search on database for the modules names of an user
	  * @param $user_id - User id to look for modules names
	  * @return an array with the module names of the given user
	  */	
	public function getUserModuleNames($user_id){

		$modules_ids = $this->getUserModules($user_id);

		$module_names = array();
		for($i = 0; $i < sizeof($modules_ids); $i++){

			$this->db->select('group_name');
			$module_id_to_get = $modules_ids[$i]['id_group'];
			
			$module_name_array = $this->db->get_where('group', array('id_group' => $module_id_to_get))->result_array();
			
			$module_names[$i] = $module_name_array[0]['group_name'];

		}
		
		$module_names = checkArray($module_names);

		return $module_names;
	}

	public function checkIfGroupExists($idGroup){

		$this->db->select('id_group');
		$searchResult = $this->db->get_where('group', array('id_group' => $idGroup));
		$foundGroup = $searchResult->row_array();

		$groupExists = sizeof($foundGroup) > 0;

		return $groupExists;
	}

	/**
	  * Search on database for the groups of an user
	  * @param $user_id - User id to look for modules
	  * @return an array with the groups of the given user
	  */
	private function getUserModules($userId){

		$this->db->select('group.*');
		$this->db->from('group');
		$this->db->join("user_group", "group.id_group = user_group.id_group");
		$this->db->where("user_group.id_user", $userId);

		$groups_for_user = $this->db->get()->result_array();

		$groups_for_user = checkArray($groups_for_user);
		return $groups_for_user;
	}
	
	/**
	 * Get all modules registered in the database
	 * @return an array with the registered modules
	 */
	public function getAllModules(){
		
		$foundModules = $this->db->get('group')->result_array();
		$foundModules = checkArray($foundModules);

		return $foundModules;
	}
}