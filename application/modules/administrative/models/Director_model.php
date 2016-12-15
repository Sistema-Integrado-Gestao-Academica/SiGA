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

	public function insertUserOnDirectorGroup($director){
		$user_group = array("id_user" => $director, "id_group" => GroupConstants::DIRECTOR_GROUP_ID);
		$saved = $this->db->insert('user_group', $user_group);

		return $saved;
	}

}