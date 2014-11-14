<?php 

class Module_model extends CI_Model {

	/**
	  * Search on database for the modules of an user
	  * @param $user_id - User id to look for modules
	  * @return an array with the modules of the user
	  */
	private function getUserModules($user_id){

		$this->db->select('id_module');
		$search_result = $this->db->get_where('user_module', array('id_user'=>$user_id));
		
		$modules_for_user = $search_result->result_array();

		return $modules_for_user;
	}

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
}