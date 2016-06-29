<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/StudentRegistrationException.php");

class Student_model extends CI_Model {

	public function getNameAndEnrollment($studentId){

		$this->db->select("users.name, course_student.enrollment");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("users.id", $studentId);
		$student = $this->db->get()->result_array();

		$student = checkArray($student);

		return $student;
	}

	public function getUserByEnrollment($enrollment){

		$this->db->select("users.id");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.enrollment", $enrollment);
		$student = $this->db->get()->result_array();

		$student = checkArray($student);

		return $student;
	}

	public function getBasicInfo($studentId){
		
		$this->db->select("users.email, users.home_phone, users.cell_phone, course_student.enrollment, course_student.id_course");
		$this->db->from("users");
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.id_user", $studentId);
		$basicInfo = $this->db->get()->result_array();
		
		$basicInfo = checkArray($basicInfo);

		return $basicInfo;
	}

	public function updateBasicInfo($basicInfo){

		$userId = $basicInfo['id_user'];
		$homePhone = $basicInfo['home_phone'];
		$cellPhone = $basicInfo['cell_phone'];

		$newInfo = array(
			'home_phone' => $homePhone->getNumber(),
			'cell_phone' => $cellPhone->getNumber()
		);

		$this->db->trans_start();

		$this->db->where("id", $userId);
		$this->db->update("users", $newInfo);

		$this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $updated = FALSE;
        }else{
        	$updated = TRUE;
        }

        return $updated;
	}
}