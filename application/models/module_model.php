<?php 

class Module_model extends CI_Model {

	/**
	  * Search on database for the permissions of an user
	  * @param $user_id - User id to look for permissions
	  * @return an array with the permissions names of the given user
	  */
	public function getUserPermissionNames($user_id){

		$this->load->model("permission_model");

		$modules_ids = $this->getUserModules($user_id);

		for($i = 0; $i < sizeof($modules_ids); $i++){
			
			$module_id_to_get = $modules_ids[$i]['id_module'];

			$module_permissions_ids = $this->permission_model->getPermissionIdsOfModule($module_id_to_get);

			$permission_names[$i] = $this->permission_model->getPermissionNamesOfModules($module_permissions_ids);

		}

		$permission_names_array = array();

		for($i = 0; $i < sizeof($permission_names); $i++){
			$permission_names_array = array_merge($permission_names_array, $permission_names[$i]);
		}

		$permissions_names = array_unique($permission_names_array);

		return $permissions_names;
	}

	/**
	  * Search on database for the modules names of an user
	  * @param $user_id - User id to look for modules names
	  * @return an array with the module names of the given user
	  */	
	public function getUserModuleNames($user_id){

		$modules_ids = $this->getUserModules($user_id);

		for($i = 0; $i < sizeof($modules_ids); $i++){

			$this->db->select('module_name');
			$module_id_to_get = $modules_ids[$i]['id_module'];
			
			$module_name_array = $this->db->get_where('module', array('id_module' => $module_id_to_get))->result_array();
			
			$module_names[$i] = $module_name_array[0]['module_name'];

		}

		return $module_names;
	}

	/**
	  * Search on database for the modules of an user
	  * @param $user_id - User id to look for modules
	  * @return an array with the modules of the given user
	  */
	private function getUserModules($user_id){

		$this->db->select('id_module');
		$search_result = $this->db->get_where('user_module', array('id_user'=>$user_id));
		
		$modules_for_user = $search_result->result_array();

		return $modules_for_user;
	}
	
	/**
	 * Get all modules registered in the database
	 * @return an array with the registered modules
	 */
	public function getAllModules(){
		
		$modules = $this->db->get('module')->result_array();
		return $modules;
		
	}
}