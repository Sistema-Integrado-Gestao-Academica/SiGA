<?php 

class Permission_model extends CI_Model {
	
	public function getAllPermissionsRoutes(){
		$allPermissions = $this->getAllPermissions();

		$permissions = array();
		foreach($allPermissions as $permission){

			$permissions[$permission['id_permission']] = $permission['route'];
		}

		return $permissions;
	}

	private function getAllPermissions(){
		$searchResult = $this->db->get('permission');

		$permissions = $searchResult->result_array();

		return $permissions;
	}

	/**
	  * Search on database for the permissions of a group
	  * @param $groups - Array with the groups of an user
	  * @return an array with the permissions of the given groups
	  */
	public function getGroupsPermissions($groups = array()){

		$this->db->select('permission.permission_name, permission.route');
		$this->db->from("permission");
		$this->db->join("group_permission", "permission.id_permission = group_permission.id_permission");
		
		$i = 0;
		foreach($groups as $group){
			
			// In case of the first where need to be a AND_WHERE clause
			if($i === 0){
				$this->db->where("group_permission.id_group", $group['id_group']);
			}else{
				$this->db->or_where("group_permission.id_group", $group['id_group']);
			}

			$i++;
		}
		
		$groupPermissions = $this->db->get()->result_array();

		return $groupPermissions;
	}

}