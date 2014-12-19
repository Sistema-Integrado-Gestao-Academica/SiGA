<?php 

class Doctorate_model extends CI_Model {

	public function getRegisteredDoctorateForCourse($courseId){
		$foundDoctorate = $this->getDoctorateForCourse($courseId);

		return $foundDoctorate;
	}

	private function getDoctorateForCourse($courseId){
		$thereIsDoctorate = $this->checkIfExistsDoctorateForThisCourse($courseId);

		if($thereIsDoctorate){
			$foundDoctorate = $this->db->get_where('academic_program', array('id_course' => $courseId));
			$foundDoctorate = $foundDoctorate->row_array();
		}else{
			$foundDoctorate = FALSE;
		}

		return $foundDoctorate;
	}

	private function checkIfExistsDoctorateForThisCourse($courseId){
		$searchResult = $this->db->get_where('doctorate', array('id_academic_program' => $courseId));
		$searchResult = $searchResult->row_array();

		$doctorateExists = sizeof($searchResult) > 0;

		return $doctorateExists;
	}
}