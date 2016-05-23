<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/DimensionException.php");

class Coordinator extends MX_Controller {

	private $COORDINATOR_GROUP = "coordenador";

	public function index() {
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/coordinator_home");
	}
	
	public function course_report(){
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/course_reports");
	}

	public function students_report(){

		$session = getSession();
		$user = $session->getUserData();
		$idCoordinator = $user->getId();
		$totalStudent = $this->getTotalStudents($idCoordinator);
		$enroledStudents = $this->getEnroledStudents($idCoordinator);
		$notEnroledStudents = $this->getNotEnroledStudents($idCoordinator);
		
		$data = array(

				'totalStudent' => $totalStudent,
				'enroledStudents' => $enroledStudents,
				'notEnroledStudents' => $notEnroledStudents
			);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/students_reports", $data);
	}
	
	public function mastermind_report(){
	
		$session = getSession();
		$user = $session->getUserData();
		$idCoordinator = $user->getId();
		$totalMasterminds = $this->getTotalMasterminds($idCoordinator);

		$data = array(
			'totalMasterminds' => $totalMasterminds

		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/mastermind_reports", $data);
	}
	
	public function secretary_report(){
		
		$session = getSession();
		$user = $session->getUserData();
		$idCoordinator = $user->getId();
		$course = $this->getCoordinatorCourseData($idCoordinator);
		$secretaries = $this->getCourseSecretaries($course['id_course']);

		$data = array(

			'course' => $course,
			'secretaries' => $secretaries

		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/secretary_reports", $data);
	}
	
	public function getCoordinatorCourseData($idCoordinator){
		$this->load->model("program/coordinator_model");
		
		$courseId = $this->coordinator_model->getCoordinatorCourse($idCoordinator);
		
		$this->load->model("program/course_model");
		$course = $this->course_model->getCourseById($courseId);
		
		return $course;
	}
	
	public function getCourseSecretaries($courseId){
		$this->load->model("program/course_model");
		
		$secretaries = $this->course_model->getCourseSecretaries($courseId);
		
		return $secretaries;
	}
	
	public function getTotalStudents($idCoordinator){
		$this->load->model("program/coordinator_model");
		
		$students = $this->coordinator_model->getTotalCourseStudents($idCoordinator);
		
		return $students;
	}
	
	public function getTotalMasterminds($idCoordinator){
		$this->load->model("program/coordinator_model");
		
		$masterminds = $this->coordinator_model->getTotalCourseMasterminds($idCoordinator);
		
		return $masterminds;
		
	}
	
	public function getMastermindStudents($mastermindId){
		$this->load->model('program/mastermind_model');

		$students = $this->mastermind_model->getStutentsByIdMastermind($mastermindId);
		
		return $students;
	}
	
	public function getEnroledStudents($idCoordinator){
		$this->load->model("program/coordinator_model");
		
		$students = $this->coordinator_model->getTotalEnroledStudents($idCoordinator);
		
		return $students;
		
	}
	
	public function getNotEnroledStudents($idCoordinator){
		$this->load->model("program/coordinator_model");
	
		$students = $this->coordinator_model->getTotalNotEnroledStudents($idCoordinator);
	
		return $students;
	
	}
	
	public function manageDimensions(){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$dimensionsTypes = $this->evaluation->getAllDimensionTypes();

		$data = array(
			'allDimensions' => $dimensionsTypes
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/manage_dimensions", $data);
	}

	public function createDimension(){

		$dimensionName = $this->input->post('new_dimension_name');
		$dimensionWeight = $this->input->post('dimension_weight');

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$wasSaved = $this->evaluation->newDimensionType($dimensionName, $dimensionWeight);

		if($wasSaved){
			$status = "success";
			$message = "Dimensão criada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar a dimensão. Não é permitido nomes iguais, cheque o nome informado.";
		}
		
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("program/coordinator/manageDimensions");
	}

	public function addDimensionToEvaluation($programId, $evaluationId, $dimensionType, $dimensionWeight = 0){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$wasAdded = $this->evaluation->addDimensionTypeToEvaluation($evaluationId, $dimensionType, $dimensionWeight);

		if($wasAdded){
			$status = "success";
			$message = "Dimensão adicionada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível adicionar a dimensão à essa avaliação.";
		}
				
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("program/coordinator/program_evaluation_index/{$programId}/{$evaluationId}");
	}

	public function coordinator_programs(){

		$session = getSession();
		$userData = $session->getUserData();
		$coordinatorId = $userData->getId();

		$this->load->model('program/program_model');
		$programs = $this->program_model->getCoordinatorPrograms($coordinatorId);
		
		$data = array(
			'coordinatorPrograms' => $coordinatorPrograms,
			'userData' => $userData,
			'programObject' => $program
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/coordinator_programs", $data);
	}

	public function program_evaluation_index($programId, $programEvaluationId){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$dimensionsTypes = $this->evaluation->getDimensionTypesForEvaluation($programEvaluationId);
		$allDimensionsTypes = $this->evaluation->getAllDimensionTypes();

		$this->load->model('program_model');
		$programData = $this->program_model->getProgramById($programId);
		$evaluation = $this->program_model->getProgramEvaluation($programEvaluationId);

		$data = array(
			'programData' => $programData,
			'programEvaluation' => $evaluation,
			'dimensionsTypes' => $dimensionsTypes,
			'allDimensionsTypes' => $allDimensionsTypes
		);
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "program/program_evaluation_index", $data);
	}

	public function evaluationDimensionData($evaluationId, $dimensionType, $programId){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$dimensionData = $this->evaluation->getDimensionData($evaluationId, $dimensionType);
		$allDimensionsTypes = $this->evaluation->getAllDimensionTypes();

		// Find the dimension name
		if($allDimensionsTypes !== FALSE){
			foreach($allDimensionsTypes as $type){
				if($type['id_dimension_type'] == $dimensionType){
					$dimensionName = $type['dimension_type_name'];
					break;
				}
			}
		}else{
			$dimensionName = FALSE;
		}
		
		$evaluationDimensions = $this->evaluation->getEvaluationDimensions($evaluationId);

		if($evaluationDimensions !== FALSE){

			$weightsSum = 0;
			foreach($evaluationDimensions as $dimension){
				$weightsSum = $weightsSum + $dimension['weight'];
			}
		}else{
			$weightsSum = 0;
		}

		$this->load->model('program/program_model');
		$evaluationData = $this->program_model->getProgramEvaluation($evaluationId);

		$data = array(
			'dimensionData' => $dimensionData,
			'evaluationData' => $evaluationData,
			'dimensionName' => $dimensionName,
			'programId' => $programId,
			'weightsSum' => $weightsSum
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "program/program_evaluation_dimension", $data);	
	}

	public function disableDimension($evaluationId, $dimensionType, $dimensionId, $programId){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$wasDisabled = $this->evaluation->disableDimension($dimensionId);

		if($wasDisabled){
			$status = "success";
			$message = "Dimensão desativada com sucesso!";
		}else{
			$status = "danger";
			$message = "Não foi possível desativar a dimensão.";
		}
		
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("program/coordinator/evaluationDimensionData/{$evaluationId}/{$dimensionType}/{$programId}");
	}

	public function createProgramEvaluation($programId){

		$this->load->model('program/program_model');
		$programData = $this->program_model->getProgramById($programId);

		$data = array(
			'programData' => $programData
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/new_program_evaluation", $data);
	}
	
	public function updateProgramArea($programId){

		$this->load->model('program/program_model');
		$programData = $this->program_model->getProgramById($programId);
		
		$programAareas = $this->program_model->getAllProgramAreas();
		if($programAreas !== FALSE){
			foreach ($programAreas as $area){

				$areas[$area['id_program_area']] = $area['area_name'];
			}
		}else{
			$areas = FALSE;
		}

		$programArea = $this->program_model->getProgramAreaByProgramId($programId);

		
		$data = array(
			'areas' => $areas,
			'currentArea' => $programArea,
			'programData' => $programData
		);
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/edit_program_area", $data);
	}

	public function newEvaluation(){

		$programId = $this->input->post('programId');
		$startYear = $this->input->post('evaluation_start_year');
		$endYear = $this->input->post('evaluation_end_year');

		$currentYear = getCurrentYear();

		$evaluation = array(
			'id_program' => $programId,
			'start_year' => $startYear,
			'end_year' => $endYear
		);

		if($currentYear !== FALSE){
			$evaluation['current_year'] = $currentYear;
		}

		$this->load->model('program_evaluation_model', 'evaluation');

		$evaluationId = $this->evaluation->saveProgramEvaluation($evaluation);

		if($evaluationId !== FALSE){

			$dimensionsOk = $this->initiateDimensionsToEvaluation($evaluationId);

			if($dimensionsOk){
				$status = "success";
				$message = "Avaliação salva com sucesso.";
			}else{
				$status = "danger";
				$message = "Não foi possível salvar algumas dimensões da avaliação. Tente novamente.";
			}

		}else{
			$status = "danger";
			$message = "Não foi possível salvar a avaliação. Tente novamente.";
		}
		
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('program/coordinator/coordinator_programs');
	}

	private function initiateDimensionsToEvaluation($evaluationId){

		$this->load->model('program/program_evaluation_model', 'evaluation');

		$dimensionsTypes = $this->evaluation->getAllDimensionTypes();

		if($dimensionsTypes !== FALSE){

			foreach($dimensionsTypes as $type){
				$this->evaluation->addDimensionTypeToEvaluation($evaluationId, $type['id_dimension_type'], $type['default_weight']);
			}

			$dimensionsSetted = $this->evaluation->checkIfHaveAllDimensions($evaluationId);
		}else{
			$dimensionsSetted = FALSE;
		}

		return $dimensionsSetted;
	}

	public function changeDimensionWeight(){

		$dimensionId = $this->input->post('dimensionId');
		$programEvaluationId = $this->input->post('programEvaluationId');
		$dimensionType = $this->input->post('dimensionType');
		$programId = $this->input->post('programId');
		$newWeight = $this->input->post('dimension_new_weight');
		
		$this->load->model('program/program_evaluation_model', 'evaluation');

		$wasChanged = $this->evaluation->updateDimensionWeight($dimensionId, $newWeight);

		if($wasChanged){
			$status = "success";
			$message = "Peso da dimensão alterado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível alterar o peso da dimensão. Tente novamente.";
		}
		
		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("program/coordinator/evaluationDimensionData/{$programEvaluationId}/{$dimensionType}/{$programId}");
	}

	public function displayProgramCourses($programId){

		$this->load->model('program/program_model');
		$programData = $this->program_model->getProgramById($programId);

		$programCourses = $this->program_model->getProgramCourses($programId);

		$data = array(
			'programCourses' => $programCourses,
			'program' => $programData
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_program_courses', $data);
	}

	public function displayCourseStudents($courseId){

		$this->load->model("program/course_model");
		$courseStudents = $this->course_model->getCourseStudents($courseId);
		$courseData = $this->course_model->getCourseById($courseId);

		$data = array(
			'courseStudents' => $courseStudents,
			'course' => $courseData
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_course_students', $data);	
	}
}
