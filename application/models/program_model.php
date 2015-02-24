<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/course.php");

class Program_model extends CI_Model {

	public function getAllPrograms(){

		$allPrograms = $this->db->get('program')->result_array();

		if(sizeof($allPrograms) > 0){
			// Nothing to do
		}else{
			$allPrograms = FALSE;
		}

		return $allPrograms;
	}

	public function addCourseToProgram($courseId, $programId){

		$course = new Course;

		$programExists = $this->checkIfProgramExists($programId);
		$courseExists = $course->checkIfCourseExists($courseId);

		$dataIsOk = $programExists && $courseExists;

		if($dataIsOk){

			$programCourse = array(
				'id_program' => $programId,
				'id_course' => $courseId
			);

			$this->db->insert('program_course', $programCourse);

			$foundProgramCourse = $this->getProgramCourse($programId, $courseId);

			if($foundProgramCourse !== FALSE){
				$wasAdded = TRUE;	
			}else{
				$wasAdded = FALSE;
			}

		}else{
			$wasAdded = FALSE;
		}

		return $wasAdded;
	}

	public function checkIfCourseIsOnProgram($programId, $courseId){

		$programCourse = $this->getProgramCourse($programId, $courseId);

		if($programCourse !== FALSE){
			$isOnProgram = TRUE;
		}else{
			$isOnProgram = FALSE;
		}

		return $isOnProgram;
	}

	private function getProgramCourse($programId, $courseId){
		
		$searchResult = $this->db->get_where('program_course', array('id_program' => $programId, 'id_course' => $courseId));
		$foundProgramCourse = $searchResult->row_array();

		if(sizeof($foundProgramCourse) > 0){
			// Nothing to do
		}else{
			$foundProgramCourse = FALSE;
		}

		return$foundProgramCourse;
	}

	public function deleteProgram($programId){

		$programExists = $this->checkIfProgramExists($programId);

		if($programExists){

			$this->dissociateCoursesOfProgram($programId);

			$this->db->delete('program', array('id_program' => $programId));

			$foundProgram = $this->getProgram(array('id_program' => $programId));

			if($foundProgram !== FALSE){
				$wasDeleted = FALSE;
			}else{
				$wasDeleted = TRUE;
			}

		}else{
			$wasDeleted = FALSE;
		}

		return $wasDeleted;
	}

	private function dissociateCoursesOfProgram($programId){

		$this->db->delete('program_course', array('id_program' => $programId));
	}

	public function checkIfProgramExists($programId){

		$program = $this->getProgram(array('id_program' => $programId));

		$programExists = $program !== FALSE;

		return $programExists;
	}

	public function saveProgram($program){

		$wasSaved = $this->insertProgram($program);

		return $wasSaved;
	}

	public function editProgram($programId, $newProgram){

		$wasUpdated = $this->updateProgram($programId, $newProgram);

		return $wasUpdated;
	}

	private function updateProgram($programId, $newProgram){

		$this->db->where('id_program', $programId);
		$this->db->update('program', $newProgram);

		$foundProgram = $this->getProgram($newProgram);

		if($foundProgram !== FALSE){
			$wasUpdated = TRUE;
		}else{
			$wasUpdated = FALSE;
		}

		return $wasUpdated;
	}

	public function getProgramById($programId){

		$program = $this->getProgram(array('id_program' => $programId));

		return $program;
	}

	private function insertProgram($program){
		
		$this->db->insert('program', $program);

		$insertedProgram = $this->getProgram($program);

		if($insertedProgram !== FALSE){
			$wasSaved = TRUE;
		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	private function getProgram($programToSearch){

		$foundProgram = $this->db->get_where('program', $programToSearch)->row_array();

		if(sizeof($foundProgram) > 0){
			// Nothing to do
		}else{
			$foundProgram = FALSE;
		}

		return $foundProgram;
	}
}
