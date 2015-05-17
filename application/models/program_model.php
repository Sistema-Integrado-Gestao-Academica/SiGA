<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/course.php");

class Program_model extends CI_Model {

	public function getAllPrograms(){

		$allPrograms = $this->db->get('program')->result_array();

		$allPrograms = checkArray($allPrograms);

		return $allPrograms;
	}

	public function getCoordinatorPrograms($coodinatorId){

		$programs = $this->db->get_where('program', array('coordinator' => $coodinatorId))->result_array();

		$programs = checkArray($programs);

		return $programs;
	}

	public function addCourseToProgram($courseId, $programId){

		$course = new Course;

		$programExists = $this->checkIfProgramExists($programId);
		$courseExists = $course->checkIfCourseExists($courseId);

		$dataIsOk = $programExists && $courseExists;

		if($dataIsOk){

			$this->db->where('id_course', $courseId);
			$this->db->update('course', array('id_program' => $programId));

			$course = new Course();
			$foundCourse = $course->getCourseById($courseId);

			if($foundCourse['id_program'] == $programId){
				$wasAdded = TRUE;
			}else{
				$wasAdded = FALSE;
			}

		}else{
			$wasAdded = FALSE;
		}

		return $wasAdded;
	}

	public function removeCourseFromProgram($courseId, $programId){

		$course = new Course;

		$programExists = $this->checkIfProgramExists($programId);
		$courseExists = $course->checkIfCourseExists($courseId);

		$dataIsOk = $programExists && $courseExists;

		if($dataIsOk){

			$this->db->where('id_course', $courseId);
			$this->db->update('course', array('id_program' => NULL));

			$course = new Course();
			$foundCourse = $course->getCourseById($courseId);

			if($foundCourse['id_program'] == $programId){
				$wasRemoved = FALSE;
			}else{
				$wasRemoved = TRUE;
			}

		}else{
			$wasRemoved = FALSE;
		}

		return $wasRemoved;
	}

	private function getProgramCourse($programId, $courseId){
		
		$searchResult = $this->db->get_where('program_course', array('id_program' => $programId, 'id_course' => $courseId));
		$foundProgramCourse = $searchResult->row_array();

		$foundProgramCourse = checkArray($foundProgramCourse);
		
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

		$foundProgram = checkArray($foundProgram);

		return $foundProgram;
	}
}
