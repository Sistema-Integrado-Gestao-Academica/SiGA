<?php
class Discipline_model extends CI_Model {

	public function listAllDisciplines(){
		$this->db->select('*');
		$this->db->from('discipline');
		$this->db->order_by("discipline_name", "asc");
		$registeredDisciplines = $this->db->get()->result_array();
		
		return $registeredDisciplines;
	}
	
	
}