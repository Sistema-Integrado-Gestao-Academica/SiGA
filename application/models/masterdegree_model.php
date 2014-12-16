<?php

class MasterDegree_model extends CI_Model {

	public function saveCourseCommonAttributes($commonAttributes, $secretary){
		
		// Save on the course table the common attributes
		$this->load->model('course_model');
		$this->course_model->saveCourse($commonAttributes);

		$courseName = $commonAttributes['course_name'];

		$this->saveCourseSecretary($courseName, $secretary);

		$insertedCourseId = $this->course_model->getCourseIdByCourseName($courseName);

		return $insertedCourseId;
	}

	public function saveCourseSpecificAttributes($courseId, $specificAttributes){

		$course_id = array('id_course' => $courseId);

		$attributes = array_merge($course_id, $specificAttributes);

		$this->saveMasterDegreeAcademicProgram($attributes);

		$this->associateMasterDegreeCourseToProgram($courseId);
	}
	
	private function saveCourseSecretary($courseName, $courseSecretary){
		$this->load->model('course_model');
		$this->course_model->saveSecretary($courseSecretary, $courseName);
	}

	private function associateMasterDegreeCourseToProgram($courseId){

		$masterDegreeAttributes = array(
			'id_academic_program' => $courseId
		);

		$this->db->insert('master_degree', $masterDegreeAttributes);
	}

	private function saveMasterDegreeAcademicProgram($courseAttributes){
		$this->db->insert('academic_program', $courseAttributes);
	}

}
	
