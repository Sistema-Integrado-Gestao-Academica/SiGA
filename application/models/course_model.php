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
	 * Delete a course by its id
	 * @param int $id_course - The course id to be deleted
	 * @return TRUE if the exclusion was made right or FALSE if it does not
	 */
	public function deleteCourseById($id_course){
		$idExists = $this->checkExistingId($id_course);
		
		$courseWasDeleted = FALSE;
		if($idExists){
			$this->db->delete('course', array('id_course' => $id_course));
			$courseWasDeleted = TRUE;
		}else{
			$courseWasDeleted = FALSE;
		}

		return $courseWasDeleted;
	}

	/**
	 * Check if a given course id exists on the database
	 * @param $course_id
	 * @return TRUE if the id exists or FALSE if does not
	 */
	public function checkExistingId($course_id){
		$foundCourse = $this->getCourseById($course_id);

		$idExistis = FALSE;
		if(sizeof($foundCourse) === 0){
			$idExistis = FALSE;
		}else{
			$idExistis = TRUE;
		}

		return $idExistis;
	}
	
	/**
	 * Function to select one course by its unique id
	 * @param int $id
	 * @return object $courseAsked if it exists, boolean $courseAsked if not exists
	 */
	public function getCourseById($id){
		$this->db->where('id_course',$id);
		$this->db->from('course');
		$courseAsked = $this->db->get()->row();
		return $courseAsked;
	}
	
	/**
	 * Function to update some course atributes
	 * @param array $courseToUpdate
	 * @return boolean $updateStatus
	 */
	public function updateCourse($id_course,$courseToUpdate){
		$courseNameToUpdate = $courseToUpdate['course_name'];
		$courseNameAlreadyExists = $this->courseNameAlreadyExists($courseNameToUpdate);
	
		$updateStatus = FALSE;
	
		if($courseNameAlreadyExists === FALSE){
			$this->db->where('id_course',$id_course);
			$this->db->update("course", $courseToUpdate);
			$updateStatus = TRUE;
		}else{
			$updateStatus = FALSE;
		}
	
		return $updateStatus;
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
	
}