<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program_model extends CI_Model {

	public function getAllPrograms(){

		$allPrograms = $this->db->get('program')->result_array();

		$allPrograms = checkArray($allPrograms);

		return $allPrograms;
	}

	public function getAllProgramAreas(){
		
		$allProgramAreas = $this->db->get('program_area')->result_array();
		
		$allProgramAreas = checkArray($allProgramAreas);
		
		return $allProgramAreas;
		
	}
	
	public function getProgramEvaluations($programId){

		$this->db->order_by("start_year", "asc");
		$evaluations = $this->db->get_where('program_evaluation', array('id_program' => $programId))->result_array();

		$evaluations = checkArray($evaluations);

		return $evaluations;
	}

	public function getProgramEvaluation($programEvaluationId){

		$evaluation = $this->db->get_where('program_evaluation', array('id_program_evaluation' => $programEvaluationId))->row_array();

		$evaluation = checkArray($evaluation);

		return $evaluation;
	}

	public function getCoordinatorPrograms($coordinatorId){

		$coordinatorPrograms = $this->db->get_where('program', array('coordinator' => $coordinatorId))->result_array();

		$coordinatorPrograms = checkArray($coordinatorPrograms);

		return $coordinatorPrograms;
	}

	public function getProgramCourses($programId){

		$this->db->select('*');
		$this->db->from('course');
		$this->db->where('course.id_program', $programId);
		$programCourses = $this->db->get()->result_array();

		$programCourses = checkArray($programCourses);

		return $programCourses;
	}

	public function parseProgramAreas($areaName){
	
		return $this->db->insert("program_area",array("area_name"=>$areaName));
	
	}
	
	public function addCourseToProgram($courseId, $programId){

		$course = new Course;

		$programExists = $this->checkIfProgramExists($programId);
		$courseExists = $course->checkIfCourseExists($courseId);

		$dataIsOk = $programExists && $courseExists;

		if($dataIsOk){

			$this->db->where('id_course', $courseId);
			$this->db->update('course', array('id_program' => $programId));

			$this->load->model("course_model");
			$foundCourse = $this->course_model->getCourseById($courseId);

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

			$this->load->model("course_model");
			$foundCourse = $this->course_model->getCourseById($courseId);

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
		$this->db->where('id_program',$programId);
		$this->db->update('course', array('id_program' => NULL));
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
	
	public function getProgramAreaByProgramId($programId){

		$program = $this->getProgram(array('id_program' => $programId));
		
		$areaId = $program['id_area'];
		
		$programArea = $this->getArea(array('id_program_area' => $areaId));
		
		return $programArea;
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
	
	private function getArea($areaToSearch){
		$this->db->select('area_name');
		$foundArea = $this->db->get_where('program_area', $areaToSearch)->row_array();
	
		$foundArea = checkArray($foundArea);
	
		return $foundArea;
	}
}
