<?php 

class Module_model extends CI_Model {

	/**
	  * Search on database for the permissions of an user
	  * @param $user_id - User id to look for permissions
	  * @return an array with the permissions names and routes of the given user
	  */
	public function getUserPermissions($user_id){

		$this->load->model("permission_model");

		$group_ids = $this->getUserModules($user_id);

		for($i = 0; $i < sizeof($group_ids); $i++){
			
			$module_id_to_get = $group_ids[$i]['id_group'];

			$module_permissions_ids = $this->permission_model->getPermissionIdsOfModule($module_id_to_get);

			$permission_names[$i] = $this->permission_model->getPermissionNamesOfModules($module_permissions_ids);
			$permission_routes[$i] = $this->permission_model->getPermissionRoutesByModules($module_permissions_ids);

		}

		$permission_names_array = array();

		for($i = 0; $i < sizeof($permission_names); $i++){
			$permission_names_array = array_merge($permission_names_array, $permission_names[$i]);
		}

		$permissions_names = array_unique($permission_names_array);


		$permission_routes_array = array();

		for($i = 0; $i < sizeof($permission_routes); $i++){
			$permission_routes_array = array_merge($permission_routes_array, $permission_routes[$i]);
		}

		$permissions_routes = array_unique($permission_routes_array);

		$permissions = array(
			'name' => $permissions_names,
			'route' => $permissions_routes
		);

		return $permissions;
	}

	public function getUserGroups($user_id){
		$this->db->select('group.*');
		$this->db->from('group');
		$this->db->join('user_group', 'group.id_group = user_group.id_group');
		$this->db->where('user_group.id_user', $user_id);

		$foundGroups = $this->db->get()->result_array();
		
		$foundGroups = $this->turnGroupArrayIntoSingleArray($foundGroups);
	
		return $foundGroups;
	}

	private function turnGroupArrayIntoSingleArray($array){

		$groupIdsArray = array();
		$groupNamesArray = array();
		for($i = 0; $i < sizeof($array); $i++){
			$groupIdsArray[$i] = $array[$i]['id_group'];
			$groupNamesArray[$i] = $array[$i]['group_name'];
		}
		
		$groups = array_combine($groupIdsArray, $groupNamesArray);
		
		return $groups;
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