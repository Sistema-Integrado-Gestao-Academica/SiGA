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

		$programWasSaved = $this->saveMasterDegreeAcademicProgram($attributes);

		$courseWasSaved = $this->associateMasterDegreeCourseToProgram($courseId);

		$masterDegreeWasSaved = $programWasSaved && $courseWasSaved;

		return $masterDegreeWasSaved;
	}

	private function saveCourseSecretary($courseName, $courseSecretary){
		$this->load->model('course_model');
		$this->course_model->saveSecretary($courseSecretary, $courseName);
	}

	private function associateMasterDegreeCourseToProgram($courseId){

		$masterDegreeAttributes = array(
			'id_academic_program' => $courseId
		);

		$insertionWasMade = $this->db->insert('master_degree', $masterDegreeAttributes);

		return $insertionWasMade;
	}

	private function saveMasterDegreeAcademicProgram($courseAttributes){
		$insertionWasMade = $this->db->insert('academic_program', $courseAttributes);

		return $insertionWasMade;
	}

}
	
