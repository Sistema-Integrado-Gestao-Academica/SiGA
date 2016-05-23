<?php
require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/CourseException.php");
require_once(APPPATH."/exception/SecretaryException.php");
require_once(MODULESPATH."auth/constants/GroupConstants.php");

class Course_model extends CI_Model {

	const COURSE_NAME_ATTR = "course_name";
	const ID_ATTR = "id_course";

	public function getCourseTeachers($courseId){

		$this->db->select('users.name, teacher_course.*');
		$this->db->from('users');
		$this->db->join("teacher_course", "users.id = teacher_course.id_user");
		$this->db->where("teacher_course.id_course", $courseId);
		$teachers = $this->db->get()->result_array();

		$teachers = checkArray($teachers);

		return $teachers;
	}

	public function getTeachers($courseId){
		
		$this->db->select('id_user');
		$this->db->from('teacher_course');
		$this->db->where('id_course', $courseId);
		$teachers = $this->db->get()->result_array();
		$teachers = checkArray($teachers);

		return $teachers;
	}

	public function enrollTeacherToCourse($teacherId, $courseId){

		$teacherToEnroll = array(
			'id_user' => $teacherId,
			'id_course' => $courseId
		);

		$this->db->insert('teacher_course', $teacherToEnroll);

		$teacherCourse = $this->getTeacherCourse($teacherToEnroll);

		$wasEnrolled = $teacherCourse !== FALSE;

		return $wasEnrolled;
	}

	public function removeTeacherFromCourse($teacherId, $courseId){

		$teacherToRemove = array(
			'id_user' => $teacherId,
			'id_course' => $courseId
		);

		$this->db->delete('teacher_course', $teacherToRemove);

		$teacherCourse = $this->getTeacherCourse($teacherToRemove);

		$wasRemoved = $teacherCourse === FALSE;

		return $wasRemoved;
	}

	private function getTeacherCourse($dataToSearch){

		$teacherCourse = $this->db->get_where('teacher_course', $dataToSearch)->row_array();

		$teacherCourse = checkArray($teacherCourse);

		return $teacherCourse;
	}

	public function defineTeacherSituation($courseId, $teacherId, $situation){

		$where = array(
			'id_user' => $teacherId,
			'id_course' => $courseId
		);

		$this->db->where($where);
		$this->db->update('teacher_course', array('situation' => $situation));

		$where['situation'] = $situation;

		$teacherCourse = $this->getTeacherCourse($where);

		$wasDefined = $teacherCourse !== FALSE;

		return $wasDefined;
	}

	public function getCourseStudents($courseId){

		$this->db->select("users.name, users.id, users.email, course_student.enroll_date, course_student.enrollment");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.id_course", $courseId);
		$students = $this->db->get()->result_array();

		$students = checkArray($students);

		return $students;
	}


	public function getCourseName($courseId){
		$courseName = $this->getCourseNameForThisCourseId($courseId);

		return $courseName;
	}

	public function getCourseIdByCourseName($courseName){

		$course = $this->getCourseForThisCourseName($courseName);

		if($course !== FALSE){
			$courseId = $course['id_course'];
		}else{
			$courseId = FALSE;
		}

		return $courseId;
	}

	public function getCourseByName($courseName){

		$foundCourse = $this->getCourseForThisCourseName($courseName);

		return $foundCourse;
	}

	private function getCourseForThisCourseName($courseName){

		$searchResult = $this->db->get_where('course', array('course_name' => $courseName));
		$foundCourse = $searchResult->row_array();

		$foundCourse = checkArray($foundCourse);

		return $foundCourse;
	}

	public function checkExistingCourseTypeId($courseTypeId){

		$foundCourseType = $this->db->get_where('course_type', array('id' => $courseTypeId))->row_array();

		$foundCourseType = checkArray($foundCourseType);

		$idExists = $foundCourseType !== FALSE;

		return $idExists;
	}

	public function getCourseTypeByCourseId($courseId){

		$courseTypeId = $this->getCourseTypeForThisCourseId($courseId);

		$courseType = $this->db->get_where('course_type', array('id' => $courseTypeId))->row_array();

		$courseType = checkArray($courseType);

		return $courseType;
	}

	public function getCoursesToProgram($programId){

		$this->db->where('id_program', $programId);
		$this->db->or_where('id_program', NULL);
		$coursesToProgram = $this->db->get('course')->result_array();

		$coursesToProgram = checkArray($coursesToProgram);

		return $coursesToProgram;
	}

	/**
	 * Get all courses registered on database
	 * @return An array with the courses. Each position is a tuple of the relation.
	 */
	public function getAllCourses(){

		$this->db->select('*');
		$this->db->from('course');
		$this->db->order_by("course_name", "asc");
		$registeredCourses = $this->db->get()->result_array();

		$registeredCourses = checkArray($registeredCourses);

		return $registeredCourses;
	}

	public function getAllCourseTypes(){

		$courseTypes = $this->db->get('course_type')->result_array();

		$courseTypes = checkArray($courseTypes);

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

			$foundCourse = $this->getCourse($courseToSave);

			if($foundCourse !== FALSE){
				$insertionStatus = TRUE;
			}else{
				$insertionStatus = FALSE;
			}

		}else{
			$insertionStatus = FALSE;
		}

		return $insertionStatus;
	}

	/**
	 * Function to manipulate secretary data to save it on database
	 * @param int $financialSecretaryUserId
	 * @param int $academicSecretaryUserId
	 * @param int $idCourse
	 * @param String $courseName
	 * @throws SecretaryException
	 * @return boolean
	 */
	public function saveCourseFinancialSecretary($financialSecretaryUserId, $idCourse, $courseName){

		$financialSecretaryToSave = array(
			"id_user"  => $financialSecretaryUserId,
			"id_group" => GroupConstants::FINANCIAL_SECRETARY_GROUP_ID,
			"id_course"=> $idCourse
		);

		try{

			$savedFinancial = $this->saveSecretary($financialSecretaryToSave);

		}catch (SecretaryException $caughtException){
			throw $caughtException;
		}

		return $savedFinancial;
	}

	public function saveCourseAcademicSecretary($academicSecretaryUserId, $idCourse, $courseName){

		$academicSecretaryToSave = array(
			"id_user"  => $academicSecretaryUserId,
			"id_group" => GroupConstants::ACADEMIC_SECRETARY_GROUP_ID,
			"id_course"=> $idCourse
		);

		try{

			$savedAcademic  = $this->saveSecretary($academicSecretaryToSave);

		}catch (SecretaryException $caughtException){
			throw $caughtException;
		}

		return $savedAcademic;
	}


	/**
	 * Function to save a secretary in the database
	 * @param array $secretary
	 * @return boolean
	 */
	private function saveSecretary($secretary){

		$alreadySavedSecretary = $this->checkExistingSecretary($secretary);

		if(!$alreadySavedSecretary){

			try{
				$save = $this->db->insert("secretary_course", $secretary);
			}catch (SecretaryException $caughtException){
				throw new SecretaryException('Não foi possível salvar este secretário.');
			}

			$alreadySavedUserGroup = $this->checkExistingSavedUserGroup($secretary['id_user'], $secretary['id_group']);

			if(!$alreadySavedUserGroup){

				try{
					$saveUserGroup  = $this->db->insert('user_group', array('id_user'=>$secretary['id_user'], 'id_group'=>$secretary['id_group']));
				}catch (SecretaryException $caughtException){
					throw new SecretaryException('Não foi possível atribuir este grupo ao usuário. Verifique a existência do mesmo.');
				}

			}else{
				// If the group is already saved, we dont need to save it again, so it's true that it's saved
				$saveUserGroup = TRUE;
			}

			if($save && $saveUserGroup){
				$insertionStatus = TRUE;
			}else{
				$insertionStatus = FALSE;
			}


		}else{
			$insertionStatus = FALSE;
		}

		return $insertionStatus;
	}

	/**
	 * Function to check in user_group table if one row of user and group id is already saved
	 * @param int $userId
	 * @param int $groupId
	 * @return boolean
	 */
	private function checkExistingSavedUserGroup($userId, $groupId){
		$check = array('id_user'=>$userId, 'id_group'=>$groupId);
		$savedRelation = $this->db->get_where('user_group', $check)->row_array();

		$exists = count($savedRelation);

		if($exists > 0){
			$relationAlreadyExists = TRUE;
		}else{
			$relationAlreadyExists = FALSE;
		}

		return $relationAlreadyExists;
	}

	/**
	 * Function to check if a secretary is already saved in the database
	 * @param array $secretary
	 * @return boolean
	 */
	private function checkExistingSecretary($secretary){
		$savedSecretary = $this->db->get_where('secretary_course', $secretary)->row_array();

		$exists = count($savedSecretary);

		if($exists > 0){
			$secretaryAlreadyExists = TRUE;
		}else{
			$secretaryAlreadyExists = FALSE;
		}

		return $secretaryAlreadyExists;
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

			$foundCourse = $this->getCourse(array('id_course' => $id_course));

			if($foundCourse !== FALSE){
				$courseWasDeleted = FALSE;
			}else{
				$courseWasDeleted = TRUE;
			}

		}else{
			$courseWasDeleted = FALSE;
		}

		return $courseWasDeleted;
	}

	public function deleteSecretary($id_course, $id_secretary){
		$idCourseExists = $this->checkExistingId($id_course);
		$idSecretaryExists = $this->checkExistingSecretaryId($id_secretary);

		$secretaryWasDeleted = FALSE;
		if($idCourseExists && $idSecretaryExists){
			$this->db->delete('secretary_course', array('id_course' => $id_course, 'id_secretary'=>$id_secretary));
			$secretaryWasDeleted = TRUE;
		}else{
			$secretaryWasDeleted = FALSE;
		}

		return $secretaryWasDeleted;

	}

	/**
	 * Check if a given course id exists on the database
	 * @param $course_id
	 * @return TRUE if the id exists or FALSE if does not
	 */
	public function checkExistingId($course_id){

		$foundCourse = $this->getCourseById($course_id);

		$idExists = $foundCourse !== FALSE;

		return $idExists;
	}

	public function checkExistingSecretaryId($secretary_id){
		$foundSecretary = $this->getSecretaryById($secretary_id);

		$idExists = FALSE;
		if(sizeof($foundSecretary) === 0){
			$idExists = FALSE;
		}else{
			$idExists = TRUE;
		}

		return $idExists;
	}

	private function getSecretaryById($secretary_id){

		$this->db->where('id_secretary',$secretary_id);
		$this->db->from('secretary_course');
		$secretaryAsked = $this->db->get()->row();

		return $secretaryAsked;
	}

	/**
	 * Function to select one course by its unique id
	 * @param int $id
	 * @return object $courseAsked if it exists, boolean $courseAsked if not exists
	 */
	public function getCourseById($id){

		$this->db->where('id_course',$id);
		$this->db->from('course');
		$courseAsked = $this->db->get()->row_array();

		$courseAsked = checkArray($courseAsked);

		return $courseAsked;
	}

	public function getCourse($courseAttributes){

		$course = $this->db->get_where('course', $courseAttributes)->row_array();

		$course = checkArray($course);

		return $course;
	}

	public function checkIfCourseExists($courseId){
		$this->db->select('id_course');
		$foundCourse = $this->db->get_where('course', array('id_course' => $courseId))->row_array();

		$courseExists = sizeof($foundCourse) > 0;

		return $courseExists;
	}

	/**
	 * Update course attributes
	 * @param String $id_course - The course id to be updated
	 * @param array $courseToUpdate - The new course data to replace the old one
	 * @return void
	 */
	public function updateCourse($idCourse, $courseToUpdate){

		$idExists = $this->checkExistingId($idCourse);

		if($idExists){
			$newCourseName = $courseToUpdate['course_name'];
			$courseNameHasChange = $this->checkIfCourseNameHasChanged($idCourse, $newCourseName);
			$courseNameAlreadyExists = $this->courseNameAlreadyExists($newCourseName);

			// The course name has to change and do not exists or already exists and do not change
			$courseNameIsOk = ($courseNameAlreadyExists && !$courseNameHasChange) || (!$courseNameAlreadyExists && $courseNameHasChange);

			if($courseNameIsOk){

				$this->updateCourseOnDb($idCourse, $courseToUpdate);

				$courseToUpdate['id_course'] = $idCourse;
				$foundCourse = $this->getCourse($courseToUpdate);

				if($foundCourse !== FALSE){
					$wasUpdated = TRUE;
				}else{
					$wasUpdated = FALSE;
				}

			}else{
				$wasUpdated = FALSE;
			}
		}else{

			$wasUpdated = FALSE;
		}

		return $wasUpdated;
	}

	public function getSecretaryByCourseId($id_course){

		$secretary = $this->db->get_where("secretary_course", array('id_course' => $id_course))->result_array();

		$secretary = checkArray($secretary);

		return $secretary;
	}

	public function getAcademicSecretaryName($id_course){

		$this->db->select('users.name');
		$this->db->from('users');
		$this->db->join('secretary_course','users.id = secretary_course.id_user');
		$this->db->where('secretary_course.id_course', $id_course);
		$this->db->where('secretary_course.id_group', GroupConstants::ACADEMIC_SECRETARY_GROUP_ID);
		$secretary = $this->db->get()->result_array();
		$secretary = checkArray($secretary);

		return $secretary;
	}

	public function getSecretaryByUserId($id_user){

		$this->db->select('id_secretary, id_group, id_course');
		$secretary = $this->db->get_where('secretary_course', array('id_user'=>$id_user))->result_array();

		$secretary = checkArray($secretary);

		return $secretary;
	}

	/**
	 * Get the course which the given user is secretary of
	 * @param $userId - Secretary id to search for courses
	 * @return an array with the found courses or FALSE if none course is found
	 */
	public function getCoursesOfSecretary($userId){

		$this->db->distinct();
		$this->db->select('course.*');
		$this->db->from('course');
		$this->db->join('secretary_course','course.id_course = secretary_course.id_course');
		$this->db->where('secretary_course.id_user', $userId);
		$courses = $this->db->get()->result_array();

		$courses = checkArray($courses);

		return $courses;
	}

	/**
	 * Update secretary atributes of a master degree course
	 * @param int $idCourseToUpdate - The course to update the secretary
	 * @param array $newSecretary - The new secretary to replace the old one
	 */
	public function updateCourseSecretary($idCourseToUpdate, $newSecretary){
		// Validate attributes
		$secretaryId = $this->getSecretaryIdByCourseId($idCourseToUpdate);
		$this->updateSecretary($secretaryId, $newSecretary);
	}

	public function getCourseSecretaries($courseId){

		$secretaries = $this->db->get_where('secretary_course', array('id_course'=>$courseId))->result_array();

		$secretaries = checkArray($secretaries);

		return $secretaries;
	}

	private function getSecretaryIdByCourseId($courseId){
		$this->db->select('id_secretary');
		$searchResult = $this->db->get_where('secretary_course', array('id_course' => $courseId));

		$searchResult = $searchResult->row_array();

		$searchResult = checkArray($searchResult);

		if($searchResult !== FALSE){
			$foundSecretary = $searchResult['id_secretary'];
		}else{
			$foundSecretary = FALSE;
		}

		return $foundSecretary;
	}

	public function updateSecretary($secretaryId, $newSecretary){
		$this->db->where('id_secretary', $secretaryId);
		$this->db->update('secretary_course', $newSecretary);
	}

	public function getCourseResearchLines($idCourse){

		$researchLines = $this->db->get_where("research_lines", array('id_course'=>$idCourse))->result_array();

		$researchLines = checkArray($researchLines);

		return $researchLines;
	}

	public function getResearchLineNameById($researchLinesId){
		$this->db->select("description");
		$researchLinesName = $this->db->get_where("research_lines", array('id_research_line'=>$researchLinesId))->row_array();

		$researchLinesName = checkArray($researchLinesName);

		return $researchLinesName;
	}

	public function saveResearchLine($newResearchLine){

		$wasSaved = $this->db->insert("research_lines", $newResearchLine);

		return $wasSaved;
	}

	public function updateResearchLine($newResearchLine, $researchLineId){
		$this->db->where('id_research_line', $researchLineId);
		$wasUpdated = $this->db->update("research_lines", $newResearchLine);

		return $wasUpdated;
	}

	public function removeCourseResearchLine($researchLineId){

		$removed = $this->db->delete("research_lines", array('id_research_line'=>$researchLineId));

		return $removed;
	}

	public function getResearchDescription($researchId,$courseId){
		$this->db->select('description');
		$description = $this->db->get_where("research_lines",array('id_research_line'=>$researchId, 'id_course'=>$courseId))->row_array();

		return $description['description'];
	}

	public function getAllResearchLines(){
		$researchLines = $this->db->get("research_lines")->result_array();

		$researchLines = checkArray($researchLines);

		return $researchLines;
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

		$foundCourseName = checkArray($foundCourseName);

		if($foundCourseName !== FALSE){
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

		$foundCourseType = checkArray($foundCourseType);

		if($foundCourseType !== FALSE){
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