<?php 

class Permission_model extends CI_Model {

	public function getPermissionIdsOfModule($module_id){

		$this->db->select('id_permission');
		$module_permissions = $this->db->get_where('module_permission', array('id_module' => $module_id))->result_array();

		return $module_permissions;
	}

	public function getPermissionNamesOfModule($module_permissions){
		
		for($i = 0; $i < sizeof($module_permissions); $i++){

			$permission = $module_permissions[$i]['id_permission'];

			$this->db->select('permission_name');	
			$permission_name = $this->db->get_where('permission', array('id_permission' => $permission))->result_array();

			$permission_names[$i] = $permission_name[0]['permission_name'];

		}

		return $permission_names;
	}

}