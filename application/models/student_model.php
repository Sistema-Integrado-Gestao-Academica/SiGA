<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/StudentRegistrationException.php");

class Student_model extends CI_Model {

	public function getBasicInfo($studentId){
		
		$this->db->select("users.email, users.home_phone, users.cell_phone, course_student.enrollment, course_student.id_course");
		$this->db->from("users");
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.id_user", $studentId);
		$basicInfo = $this->db->get()->result_array();
		
		$basicInfo = checkArray($basicInfo);

		return $basicInfo;
	}	
}