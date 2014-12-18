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

	public function getRegisteredMasterDegreeForThisCourseId($courseId){
		// Validate $courseId
		$registeredMasterDegree = $this->getRegisteredMasterDegree($courseId);

		return $registeredMasterDegree;
	}

	/**
	 * Get the registered master degree course, if it exists
	 * @param $courseId - The course to look for master degree program
	 * @return  an array with the attributes of the found master degree course if it exists
	 * @return FALSE if there is no master degree course for this course id
	 */
	private function getRegisteredMasterDegree($courseId){
		$thereIsMasterDegree = $this->checkIfExistsMasterDegreForThisCourse($courseId);

		if($thereIsMasterDegree){	
			$searchResult = $this->db->get_where('academic_program', array('id_course' => $courseId));
			$foundMasterDegree = $searchResult->row_array();
		}else{
			$foundMasterDegree = FALSE;
		}

		return $foundMasterDegree;
	}

	/**
	 * Check if there is a master degree associated to the given course id
	 * @param $courseId - The course to look for master degree courses
	 * @return TRUE if there is a master degree course associated to this course id or FALSE if does not
	 */
	private function checkIfExistsMasterDegreForThisCourse($courseId){
		$this->db->select('id_master_degree');
		$searchResult = $this->db->get_where('master_degree', array('id_academic_program' => $courseId));
		$searchResult = $searchResult->row_array();

		$existsMasterDegree = sizeof($searchResult) > 0;

		return $existsMasterDegree;
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
	
