<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Director_model extends CI_Model {

	public function getCurrentDirector(){
		$this->db->select('id, name');
		$this->db->from('users');
		$this->db->join('user_group', "users.id = user_group.id_user");
		$this->db->where('user_group.id_group', 13);
		
		$currentDirector = $this->db->get()->row();

		return $currentDirector;
	}

	public function insertUserOnDirectorGroup($newDirector, $currentDirector){

		$deleted = TRUE;
		if(!is_null($currentDirector)){
			$userToDelete = array(
							"id_user" => $currentDirector, 
							"id_group" => GroupConstants::DIRECTOR_GROUP_ID);
			$deleted = $this->db->delete('user_group', $userToDelete); 
		}

		if($deleted){
			$userGroup = array(
						"id_user" => $newDirector, 
						"id_group" => GroupConstants::DIRECTOR_GROUP_ID);
			$saved = $this->db->insert('user_group', $userGroup);
		}
		else{
			$saved = FALSE;
		}

		return $saved;
	}

}