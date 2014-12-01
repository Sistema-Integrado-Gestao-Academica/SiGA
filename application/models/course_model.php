<?php 

class Course_model extends CI_Model {
	
	/**
	 * Get all course types registered on database
	 * @return An array with the course types. Each position is a tuple of the relation.
	 */
	public function getAllCourseTypes(){
		$this->db->select('id_course_type, course_type_name');
		$this->db->from('course_type');
		$courseTypes = $this->db->get()->result_array();
		
		return $courseTypes;
	}

	/**
	 * Save a course on the database
	 * @param $courseToSave - Array with the attributes of the course
	 * @return TRUE if the insertion was made or FALSE if it does not
	 */
	public function saveCourse($courseToSave){
		
		$courseNameToSave = $courseToSave['course_name'];
		$courseNameAlreadyExists = $this->courseNameAlreadyExists($courseNameToSave);
	
		$insertionStatus = FALSE;

		if($courseNameAlreadyExists === FALSE){
			$this->db->insert("course", $courseToSave);
			$insertionStatus = TRUE;
		}else{
			$insertionStatus = FALSE;
		}

		return $insertionStatus;
	}

	/**
	 * Get all courses registered on database
	 * @return An array with the courses. Each position is a tuple of the relation.
	 */
	public function getAllCourses(){
		$this->db->select('*');
		$this->db->from('course');
		$registeredCourses = $this->db->get()->result_array();

		return $registeredCourses;
	}


	/**
	 * Check if the given course name already exists on database
	 * @param $courseName - The course name to check
	 * @return TRUE if already exists or FALSE if does not exists
	 */
	private function courseNameAlreadyExists($courseName){

		$this->db->select('course_name');
		$this->db->from('course');
		$this->db->where('course_name', $courseName);
		$searchResult = $this->db->get();
		
		$courseNameAlreadyExists = FALSE;

		if($searchResult->num_rows() > 0){
			$courseNameAlreadyExists = TRUE;
		}else{
			$courseNameAlreadyExists = FALSE;
		}

		return $courseNameAlreadyExists;
	}
	
	public function deleteCourseById($id_course){
		$deletedCourse = $this->db->delete('course', array('id_course' => $id_course));
		return $deletedCourse;
	}
	

}