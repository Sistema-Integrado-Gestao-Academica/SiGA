<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Semester_model extends CI_Model {

	public function getCurrentSemester(){

		$this->db->select('semester.*');
		$this->db->from('semester');
		$this->db->join('current_semester', 'current_semester.id_semester = semester.id_semester');
		$currentSemester = $this->db->get()->row_array();

		if(sizeof($currentSemester) > 0){
			// Nothing to do
		}else{
			$currentSemester = FALSE;
		}

		return $currentSemester;
	}
}