<?php 

class Course_model extends CI_Model {
	
	public function getAllCourseTypes(){
		$this->db->select('id_course_type, name_course_type');
		$this->db->from('course_type');
		$courseTypes = $this->db->get()->result_array();
		
		return $courseTypes;
	}
	
}