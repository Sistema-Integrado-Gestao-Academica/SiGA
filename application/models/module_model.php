<?php 

class Module_model extends CI_Model {

	public function addGroupToUser($groupToUser){

		$this->db->insert('user_group', $groupToUser);
	}
	
	public function deleteGroupOfUser($userGroup){

		$this->db->delete('user_group', $userGroup);
	}

	/**
	  * Search on database for the permissions of an user
	  * @param $user_id - User id to look for permissions
	  * @return an array with the permissions names and routes of the given user
	  */
	public function getUserPermissions($user_id){

		$this->load->model("permission_model");

		$groups = $this->getUserModules($user_id);

		$groupPermissions = $this->permission_model->getGroupsPermissions($groups);

		return $groupPermissions;
	}

	public function getUserGroups($user_id){
		$this->db->select('group.*');
		$this->db->from('group');
		$this->db->join('user_group', 'group.id_group = user_group.id_group');
		$this->db->where('user_group.id_user', $user_id);

		$foundGroups = $this->db->get()->result_array();

		return $foundGroups;
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

		return $module_names;
	}

	/**
	  * Search on database for the groups of an user
	  * @param $user_id - User id to look for modules
	  * @return an array with the groups of the given user
	  */
	private function getUserModules($user_id){

		$this->db->select('id_group');
		$search_result = $this->db->get_where('user_group', array('id_user'=>$user_id));
		
		$groups_for_user = $search_result->result_array();

		return $groups_for_user;
	}
	
	/**
	 * Get all modules registered in the database
	 * @return an array with the registered modules
	 */
	public function getAllModules(){
		
		$modules = $this->db->get('group')->result_array();
		return $modules;
		
	}
}