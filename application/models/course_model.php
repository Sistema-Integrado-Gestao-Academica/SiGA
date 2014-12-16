<?php 
require_once(APPPATH."/exception/CourseNameException.php");
class Course_model extends CI_Model {


	public function getCourseIdByCourseName($courseName){

		$courseId =$this->getCourseIdForThisCourseName($courseName);

		return $courseId;
	}

	private function getCourseIdForThisCourseName($courseName){
		$this->db->select('id_course');
		$searchResult = $this->db->get_where('course', array('course_name' => $courseName));
		$searchResult = $searchResult->row_array();

		$courseId = $searchResult['id_course'];

		return $courseId;
	}

	// /**
	//  * Get the course type name for a given course type id
	//  * @param $course_type_id - The course type id to look for a name
	//  * @return The found course type name if it exists or FALSE if does not
	//  */
	// public function getCourseTypeNameForThisId($course_type_id){
		
	// 	$idExists = $this->checkExistingCourseTypeId($course_type_id);

	// 	if($idExists){

	// 		$this->db->select('course_type_name');
	// 		$this->db->from('course_type');
	// 		$this->db->where('id_course_type', $course_type_id);
	// 		$searchResult = $this->db->get()->row_array();

	// 		$foundCourseTypeName = $searchResult['course_type_name'];

	// 	}else{
	// 		$foundCourseTypeName = FALSE;
	// 	}

	// 	return $foundCourseTypeName;
	// }

	public function getCourseTypeById($course_type_id){

		$this->db->where('id_course_type', $course_type_id);
		$this->db->from('course_type');
		$searchResult = $this->db->get()->row();
		
		return $searchResult;
	}

	public function checkExistingCourseTypeId($course_type_id){

		$foundType = $this->getCourseTypeById($course_type_id);

		$idExists = FALSE;
		if(sizeof($foundType) === 0){
			$idExists = FALSE;
		}else{
			$idExists = TRUE;
		}

		return $idExists;
	}

	// /**
	//  * Get all course types registered on database
	//  * @return An array with the course types. Each position is a tuple of the relation.
	//  */
	// public function getAllCourseTypes(){
	// 	$this->db->select('id_course_type, course_type_name');
	// 	$this->db->from('course_type');
	// 	$courseTypes = $this->db->get()->result_array();
		
	// 	return $courseTypes;
	// }

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
	
	public function saveSecretary($secretary, $courseName){
		$this->db->select('id_course');
		$courseId = $this->db->get_where('course',array('course_name'=> $courseName))->row_array();
		
		$saveSecretary = array_merge($secretary,$courseId);
		$save = $this->db->insert("secretary_course", $saveSecretary);
		
		if($save){
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
	 * Update course attributes
	 * @param String $id_course - The course id to be updated
	 * @param array $courseToUpdate - The new course data to replace the old one
	 * @return void
	 * @throws CourseNameException
	 */
	public function updateCourse($id_course, $courseToUpdate){

		$courseNameToUpdate = $courseToUpdate['course_name'];
		$courseTypeIdToUpdate = $courseToUpdate['course_type_id'];
		
		$courseNameHasChanged = $this->checkIfCourseNameHasChanged($id_course, $courseNameToUpdate);
		$courseTypeHasChanged = $this->checkIfCourseTypeHasChanged($id_course, $courseTypeIdToUpdate);

		// Check what attribute has changed
		if($courseNameHasChanged){

			$courseNameNotAlreadyExists = !($this->courseNameAlreadyExists($courseNameToUpdate));

			// Check if the new course name does not exists yet on DB
			if($courseNameNotAlreadyExists){

				$this->updateCourseOnDb($id_course, $courseToUpdate);

			}else{
				$errorMessage = "O nome do curso '".$courseNameToUpdate."' já existe.";
				throw new CourseNameException($errorMessage);
			}
			
		}else if($courseTypeHasChanged){

			// Take out of the array the course name once it has not changed, to update only the course type
			unset($courseToUpdate['course_name']);

			$this->updateCourseOnDb($id_course, $courseToUpdate);

		}else{
			$errorMessage = "Nenhum alteração foi feita no curso '".$courseNameToUpdate."'";
			throw new CourseNameException($errorMessage);		
		}

	}
	
	public function getSecretaryByCourseId($id_course){
		
		$this->db->select('id_secretary, id_group, id_user');
		$secretary_return = $this->db->get_where('secretary_course', array('id_course'=>$id_course))->row_array();
		
		return $secretary_return;
		
	}
	
	/**
	 * Update secretary atributes of one course
	 * @param array $secretary 
	 * @param int $id_secretary
	 */
	public function updateSecretary(){
		
	}

	/**
	 * Update a course on DB
	 * @param String $id_course - The course id to be updated
	 * @param array $courseToUpdate - The new course data to replace the old one
	 * @return void
	 */
	private function updateCourseOnDb($id_course, $newCourseData){
		$this->db->where('id_course',$id_course);
		$this->db->update("course", $newCourseData);
	}

	/**
	 * Check if the course name from a given course id has changed in comparison with the new course name
	 * @param $course_id - The course id to get the old course name
	 * @param $newCourseName - The new course name that will replace the old one
	 * @return TRUE if the course name has changed or FALSE if does not
	 */
	private function checkIfCourseNameHasChanged($course_id, $newCourseName){
		$oldCourseName = $this->getCourseNameForThisCourseId($course_id);

		if($oldCourseName == $newCourseName){
			$hasChanged = FALSE;
		}else{
			$hasChanged = TRUE;
		}

		return $hasChanged;
	}

	/**
	 * Get the course name registered for a given course id
	 * @param $id_course - The course id to look for the course name 
	 * @return a String with the registered course name for the given course id if found, or FALSE if does not
	 */
	private function getCourseNameForThisCourseId($id_course){
		$this->db->select('course_name');
		$searchResult = $this->db->get_where('course', array('id_course' => $id_course));

		$foundCourseName = $searchResult->row_array();

		if(sizeof($foundCourseName) > 0){
			$foundCourseName = $foundCourseName['course_name'];
		}else{
			$foundCourseName = FALSE;
		}

		return $foundCourseName;
	}

	/**
	 * Check if the course type from a given course id has changed in comparison with the new course type id
	 * @param $course_id - The course id to get the old course type id
	 * @param $newCourseTypeId - The new course type id that will replace the old one
	 * @return TRUE if the course type has changed or FALSE if does not
	 */
	private function checkIfCourseTypeHasChanged($course_id, $newCourseTypeId){
		$oldCourseTypeId = $this->getCourseTypeForThisCourseId($course_id);

		if($oldCourseTypeId == $newCourseTypeId){
			$hasChanged = FALSE;
		}else{
			$hasChanged = TRUE;
		}

		return $hasChanged;
	}

	/**
	 * Get the course type id registered for a given course id
	 * @param $id_course - The course id to look for the course type 
	 * @return a String with the registered course type for the given course id if found, or FALSE if does not
	 */
	private function getCourseTypeForThisCourseId($id_course){
		$this->db->select('course_type_id');
		$searchResult = $this->db->get_where('course', array('id_course' => $id_course));

		$foundCourseType = $searchResult->row_array();

		if(sizeof($foundCourseType) > 0){
			$foundCourseType = $foundCourseType['course_type_id'];
		}else{
			$foundCourseType = FALSE;
		}

		return $foundCourseType;
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