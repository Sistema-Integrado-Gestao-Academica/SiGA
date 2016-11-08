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

		$this->load->model('program/programevaluation_model', 'evaluation');

		$dimensionsTypes = $this->evaluation->getAllDimensionTypes();

		$data = array(
			'allDimensions' => $dimensionsTypes
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/manage_dimensions", $data);
	}

	public function createDimension(){

		$dimensionName = $this->input->post('new_dimension_name');
		$dimensionWeight = $this->input->post('dimension_weight');

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		$this->load->model('program/programevaluation_model', 'evaluation');

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
		$coordinatorPrograms = $this->program_model->getCoordinatorPrograms($coordinatorId);

		$data = array(
			'coordinatorPrograms' => $coordinatorPrograms,
			'userData' => $userData
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/coordinator_programs", $data);
	}

	public function program_evaluation_index($programId, $programEvaluationId){

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "program/coordinator/program_evaluation_index", $data);
	}

	public function evaluationDimensionData($evaluationId, $dimensionType, $programId){

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "program/program/program_evaluation_dimension", $data);
	}

	public function disableDimension($evaluationId, $dimensionType, $dimensionId, $programId){

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		$programAreas = $this->program_model->getAllProgramAreas();
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

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		$this->load->model('program/programevaluation_model', 'evaluation');

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

		$this->load->model('program/programevaluation_model', 'evaluation');

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

	
    public function evaluationsReports(){

        $session = getSession();
        $user = $session->getUserData();
        $coordinatorId = $user->getId();
        
        $this->load->model("program/program_model");
        $programs = $this->program_model->getCoordinatorPrograms($coordinatorId);

        $data = array(
            'programs' => $programs
        );

        loadTemplateSafelyByGroup(GroupConstants::COORDINATOR_GROUP, "coordinator/evaluation_reports", $data);
        
    }

    public function programEvaluationsReport($programId){

        $this->load->model("program/program_model");
        $this->load->model("program/baseprogram_model");
        
        $basePrograms = $this->baseprogram_model->getBasePrograms();

        // Get evaluations periods
        $evaluationPeriods = $this->getEvaluationPeriods($programId);
	    $program = $this->program_model->getProgramById($programId);
        
        if(!empty($evaluationPeriods)){

        	$lastPeriod = count($evaluationPeriods);
        	$lastPeriod = $evaluationPeriods[$lastPeriod];
	   
		
			// Get the first year of evaluations
			$firstPeriod = array_shift($evaluationPeriods); 
			$firstYearOfEvaluations = array_shift($firstPeriod); 

			$currentYear = getCurrentYear();

	        $data = array(
	            'currentYear' =>$currentYear,
	            'minimumYear' =>$firstYearOfEvaluations,
	            'program' => $program,
        		'basePrograms' => $basePrograms
	        );
	    	
	    	$data = $this->getProductionsInformationByPeriod($data, $lastPeriod, $programId);
        }
        else{
        	$data = array(
	            'currentYear' => FALSE,
	            'program' => $program
	        );
        }

     	loadTemplateSafelyByGroup(GroupConstants::COORDINATOR_GROUP, "coordinator/program_evaluation_report", $data);
    }

    public function getProductionsInformationByPeriod($data, $period, $programId){

		// Get productions
        $this->load->model("program/production_model");
        $productions = $this->production_model->getProgramsProduction($programId, $period);
        
        // Get collaboration indicator
        $collaborationIndicators = $this->getCollaborationIndicatorByProgram($programId, $period);

        // Get chart information
    	$chartData = $this->assembleChartData($productions, $period);
	    
	    $data['collaborationIndicators'] = $collaborationIndicators;
	    $data['chartData'] = $chartData;

        return $data;
    }


    public function changeChart(){
        
        $this->load->model("program/program_model");
    	
    	$startYear = $this->input->post("startYear");
    	$endYear = $this->input->post("endYear");
    	
    	$period = getYearsOfAPeriod($startYear, $endYear);

    	$programId = $this->input->post("programId");

    	$data = $this->getProductionsInformationByPeriod(array(), $period, $programId);

    	$json = json_encode($data);
    	echo $json;
    }


    public function changeCollaborationTable(){

    	$collaborationIndicators = $this->input->post("collaborationIndicators");

    	echo collaborationIndicatorTable($collaborationIndicators);
    }


    private function getCollaborationIndicatorByProgram($programId, $period){
        
		// Get productions
        $this->load->model("program/production_model");
        $productions = $this->production_model->getProgramsProduction($programId, $period);

        $numberOfTeachers = $this->program_model->countNumberOfTeachersOnProgram($programId);

        $filteredProductions = $this->countProductionsByYear($productions, $period);
        $collaborationIndicators = array();
        
        if(!empty($filteredProductions)){
        	foreach ($filteredProductions as $year => $pontuation) {
        		$collaborationIndicators[$year] = $pontuation/$numberOfTeachers;
        	}
        }

        return $collaborationIndicators;
    }

    private function getEvaluationPeriods($programId){
        
        $evaluations = $this->program_model->getProgramEvaluations($programId);

        $evaluationsPeriods = array();
        $periods = array();
        if($evaluations !== FALSE){

	        foreach ($evaluations as $evaluation) {
	        	$id = $evaluation['id_program_evaluation'];
	        	$startYear = $evaluation['start_year'];
	        	$endYear = $evaluation['end_year'];
	        	$evaluationsPeriods[$id] = getYearsOfAPeriod($startYear, $endYear);
	        }
        }


    	return $evaluationsPeriods;
    }

    private function assembleChartData($productions, $period){


        $columns = array();

        // Put year of period on X axis
        $xaxis = array("x");
        if(!empty($period)){
        	foreach ($period as $year) {
        		$xaxis[] = (string) $year;
        	}
        }

        $pontuationOfProductionsByYear = $this->countProductionsByYear($productions, $period);

        $points = array("Pontuações");
        foreach ($pontuationOfProductionsByYear as $year => $pontuation) {
            $points[] = $pontuation;
        }


        $columns[] = $xaxis;
        $columns[] = $points;

        $chartData = array(
        	'x' => 'x',
            'columns' => $columns
        );

        $chartData = json_encode($chartData);

        return $chartData;
    }

    private function countProductionsByYear($productions, $period){
        
        $filteredProductions = array();

        // Initializing year productions with zero
        if(!empty($period)){
        	foreach ($period as $year) {
        		$filteredProductions[$year] = 0;
        	}
        }
		if(!empty($productions)){
		    foreach ($productions as $production) {
		        $productionYear = $production['year'];

		        if($productionYear !== NULL){
		        	$inArray = in_array($productionYear, $period);
		        	$pontuation = $this->getProductionPontuation($production['qualis']);
		            $filteredProductions[$productionYear] += $pontuation;
		        }
		    }
		}

        return $filteredProductions;
    }


    /* Get the pontuation of a year based on qualis
		A1 - 100; A2 - 85; B1 - 70; B2 - 55; B3 - 40; B4 - 25; B5 - 10;	C - 0
	*/
    private function getProductionPontuation($qualis){
    	
    	if($qualis !== NULL){

    		switch ($qualis) {
    			case 'A1':
    				$pontuation = 100;
    				break;
    			case 'A2':
    				$pontuation = 85;
    				break;

				case 'B1':
    				$pontuation = 70;
					break;

				case 'B2':
    				$pontuation = 55;
    				break;

    			case 'B3':
    				$pontuation = 40;
    				break;

    			case 'B4':
    				$pontuation = 25;
    				break;

    			case 'B5':
    				$pontuation = 10;
    				break;

    			default:
    				$pontuation = 0;
    				break;
    		}

    	}
    	else{
    		$pontuation = 0;
    	}

    	return $pontuation;
    }

}
