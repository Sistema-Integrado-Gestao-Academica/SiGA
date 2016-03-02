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

	public function getUserGroups($user_id){
		$this->db->select('group.*');
		$this->db->from('group');
		$this->db->join('user_group', 'group.id_group = user_group.id_group');
		$this->db->where('user_group.id_user', $user_id);

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
	
	/**
	 *	LINES 70 -> 180   ARE ALL DEPRECATED CODE 
	 * 
	 *
	 * Function to save new secretary groups for one course
	 * @param String $courseName
	 * @return boolean 
	 *
	public function saveNewCourseGroups($courseName){
		$courseName = strtolower($courseName);
		$separatedName = explode(' ', $courseName);
		
		if ($separatedName){
			$groupsNames = $this->prepareGroupName($separatedName);
		}else {
			$groupsNames = $this->prepareGroupName($courseName,TRUE);
		}
		
		
		$savedGroups = $this->saveNewGroupsOnDB($groupsNames);
		$grantedPremissions = $this->grantGroupsPermissions($groupsNames);
		
		$savedGroupsAndPermissions = $savedGroups && $grantedPremissions;
		
		return $savedGroupsAndPermissions;
	}
	
	/**
	 * Function to get a course name and make a new group name by taking first 3 letters of any name
	 * and concatenate them with 'Academic' and 'Financial' to make new academic and financial groups for the course
	 * @param mixed $separatedName
	 * @param boolean $singleName
	 * @return array:string
	 *
	public function prepareGroupName($separatedName,$singleName = FALSE){
		
		if($singleName){
			$letters = str_split($separatedName);
			$newGroupName = $letters[0].$letters[1].$letters[2];
		}else{
			$length = count($separatedName);
			$newGroupName = '';
			for ($i=0; $i < $length; $i++){
				$letters = str_split($separatedName[$i]);
				$first3Letters[$i] = $letters[0].$letters[1].$letters[2];
				$newGroupName = $newGroupName . $first3Letters[$i];
			}
			
		}
		
		$academicGroupName = $newGroupName."Academico";
		$financialGroupName = $newGroupName."Financeiro";
		
		$groupNames = array('academic'  => $academicGroupName,
							'financial' => $financialGroupName);
		
		return $groupNames;
	}
	
	public function saveNewGroupsOnDB($groupsNames){
		$academic = array('group_name' => $groupsNames['academic']);
		$financial = array('group_name' => $groupsNames['financial']);
		
		$savedAcademic = $this->db->insert('group',$academic);
		$savedFinancial = $this->db->insert('group',$financial);
		
		$savedGroups = $savedAcademic && $savedFinancial;
		
		return $savedGroups;
	}
	
	private function grantGroupsPermissions($groupsNames){
		
		$idGroups = $this->getGroupIdByName($groupsNames);
		
		/**
		 * Granting permissions to academic secretary
		 * Academic permissions ids: 2, 3, 4, 5, 6, 9
		 *
		$academicPermissions = array(
				
				array('id_group'=>$idGroups['academic'], 'id_permission'=>2),
				array('id_group'=>$idGroups['academic'], 'id_permission'=>3),
				array('id_group'=>$idGroups['academic'], 'id_permission'=>4),
				array('id_group'=>$idGroups['academic'], 'id_permission'=>5),
				array('id_group'=>$idGroups['academic'], 'id_permission'=>6),
				array('id_group'=>$idGroups['academic'], 'id_permission'=>9)
				
		);
		
		$savedAcademicPermissions = $this->db->insert_batch('group_permission', $academicPermissions);
		
		/**
		 * Granting permissions to financial secretary
		 * Academic permissions ids: 2 , 4 , 7  
		 *
		$financialPermissions = array(
			
				array('id_group'=>$idGroups['financial'], 'id_permission'=>2),
				array('id_group'=>$idGroups['financial'], 'id_permission'=>4),
				array('id_group'=>$idGroups['financial'], 'id_permission'=>7)
				
		);
		
		$savedFinancialPermissions = $this->db->insert_batch('group_permission', $financialPermissions);
		
		$savedPermissions = $savedAcademicPermissions && $savedFinancialPermissions;
		
		return $savedPermissions;
	}
	*/
	
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
		
		$modules = $this->db->get('group')->result_array();
		$modules = checkArray($modules);
		return $modules;
	}
}