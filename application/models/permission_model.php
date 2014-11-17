<?php 

class Permission_model extends CI_Model {

	/**
	  * Search on database for the permissions id's of a module
	  * @param $module_id - Module id to look for permissions id's
	  * @return an array with the permissions of the given module
	  */
	public function getPermissionIdsOfModule($module_id){

		$this->db->select('id_permission');
		$module_permissions = $this->db->get_where('module_permission', array('id_module' => $module_id))->result_array();

		return $module_permissions;
	}

	/**
	  * Search on database for the permissions names of a set of permissions id's from a module
	  * @param $module_permissions - Array with all permissions id's of a module
	  * @return an array with all the permissions names of the the given permissions id's
	  */
	public function getPermissionNamesOfModules($module_permissions){
		
		for($i = 0; $i < sizeof($module_permissions); $i++){

			$permission = $module_permissions[$i]['id_permission'];

			$this->db->select('permission_name');	
			$permission_name = $this->db->get_where('permission', array('id_permission' => $permission))->result_array();

			$permission_names[$i] = $permission_name[0]['permission_name'];

		}

		return $permission_names;
	}

}