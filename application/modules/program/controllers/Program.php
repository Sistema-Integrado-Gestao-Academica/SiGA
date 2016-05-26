<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class Program extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('program/program_model');
		
	}

	public function index(){

		$programs = $this->getAllPrograms();

		$data = array(
			'programs' => $programs
		);

		loadTemplateSafelyByPermission(PermissionConstants::PROGRAMS_PERMISSION,'program/program/index', $data);
	}

	public function getAllPrograms(){

		$programs = $this->program_model->getAllPrograms();

		return $programs;
	}

	public function getAllProgramAreas(){

		
		$programAreas = $this->program_model->getAllProgramAreas();

		if($programAreas !== FALSE){
			foreach ($programAreas as $areas){

				$areasResult[$areas['id_program_area']] = $areas['area_name'];
			}
		}else{
			$areasResult = FALSE;
		}

		return $areasResult;
	}

	public function getCoordinatorPrograms($coordinatorId){

		
		$programs = $this->program_model->getCoordinatorPrograms($coordinatorId);

		return $programs;
	}

	public function getProgramEvaluations($programId){

		
		$programEvaluations = $this->program_model->getProgramEvaluations($programId);

		return $programEvaluations;
	}

	public function getProgramEvaluation($programEvaluationId){

		
		$programEvaluation = $this->program_model->getProgramEvaluation($programEvaluationId);

		return $programEvaluation;
	}

	public function getProgramById($programId){

		
		$programs = $this->program_model->getProgramById($programId);

		return $programs;

	}

	public function getProgramAreaByProgramId($programId){
		
		$programArea = $this->program_model->getProgramAreaByProgramId($programId);

		return $programArea;

	}

	public function getProgramCourses($programId){

		
		$programCourses = $this->program_model->getProgramCourses($programId);

		return $programCourses;
	}

	public function addCourseToProgram($courseId, $programId){

		
		$wasAdded = $this->program_model->addCourseToProgram($courseId, $programId);

		if($wasAdded){
			$insertStatus = "success";
			$insertMessage = "Curso adicionado com sucesso ao programa.";
		}else{
			$insertStatus = "danger";
			$insertMessage = "Não foi possível adicionar o curso informado.";
		}

		$session = getSession();
		$session->showFlashMessage($insertStatus, $insertMessage);
		redirect("program/editProgram/{$programId}");
	}

	public function getInformationAboutPrograms(){
		
		$programs = $this->program_model->getAllPrograms();
		$quantityOfPrograms = count($programs);
		
		//  Contains the courses, research lines and teachers
		$coursesPrograms = $this->getProgramsCoursesInfo($programs);		
		$programs = $this->getProgramsWithInformation($programs, $coursesPrograms);

		$coordinators = $this->getCoordinatorsForHomepage($programs);

		$data = array (
			'programs' => $programs,
			'quantityOfPrograms' => $quantityOfPrograms,
			'coordinators' => $coordinators,
			'coursesPrograms' => $coursesPrograms
		);

		return $data;
	}

	public function removeCourseFromProgram($courseId, $programId){

		
		$wasRemoved = $this->program_model->removeCourseFromProgram($courseId, $programId);

		if($wasRemoved){
			$removeStatus = "success";
			$removeMessage = "Curso removido com sucesso do programa.";
		}else{
			$removeStatus = "danger";
			$removeMessage = "Não foi possível adicionar o curso informado.";
		}

		$session = getSession();
		$session->showFlashMessage($removeStatus, $removeMessage);
		redirect("program/editProgram/{$programId}");
	}

	public function editProgram($programId){

		$program = $this->program_model->getProgramById($programId);

		$this->load->model("auth/module_model");
		$foundGroup = $this->module_model->getGroupByGroupName(GroupConstants::COORDINATOR_GROUP);

		$this->load->module("auth/userController");
		$userGroup = $this->usercontroller->getGroup();
		
		if($foundGroup !== FALSE){
			$users = $this->usercontroller->getUsersOfGroup($foundGroup['id_group']);

			if($users !== FALSE){

				$usersForCoordinator = array();
				foreach($users as $user){
					$usersForCoordinator[$user['id']] = $user['name'];
				}
			}else{
				$usersForCoordinator = FALSE;
			}

		}else{
			$usersForCoordinator = FALSE;
		}

		$this->load->model("program/course_model");
		$courses = $this->course_model->getCoursesToProgram($programId);

		$data = array(
			'programData' => $program,
			'users' => $usersForCoordinator,
			'courses' => $courses,
			'userGroup' => $userGroup
 		);

		$groups = array(GroupConstants::ACADEMIC_SECRETARY_GROUP,GroupConstants::ADMIN_GROUP);

		loadTemplateSafelyByGroup($groups, "program/program/edit_program", $data);
	}

	public function updateProgram(){

		$programId = $this->input->post('programId');

		$programDataIsOk = $this->validatesNewProgramData();

		$session = getSession();
		if($programDataIsOk){

			$programName = $this->input->post('program_name');
			$programAcronym = $this->input->post('program_acronym');
			$programCoordinator = $this->input->post('program_coordinator');
			$openingYear = $this->input->post('opening_year');
			$programContact = $this->input->post('program_contact');
			$programHistory = $this->input->post('program_history');
			$programSummary = $this->input->post('program_summary');

			$dataIsOk = $this->verifyTheNewData($programId, $programName, $programAcronym);		

			if($dataIsOk){

				$programData = array(
					'program_name' => $programName,
					'acronym' => $programAcronym,
					'coordinator' => $programCoordinator,
					'opening_year' => $openingYear,
					'contact' => $programContact,
					'history' => $programHistory,
					'summary' => $programSummary
				);


				$wasUpdated = $this->program_model->editProgram($programId, $programData);

				if($wasUpdated){
					$insertStatus = "success";
					$insertMessage = "Programa atualizado com sucesso!";
				}else{
					$insertStatus = "danger";
					$insertMessage = "Não foi possível atualizar os registros. Tente novamente.";
				}
			}
			else{
				$insertStatus = "danger";
				$insertMessage = "Esse programa já está cadastrado.";
				$session->showFlashMessage($insertStatus, $insertMessage);
				redirect("program/editProgram/{$programId}");
			}
			$session->showFlashMessage($insertStatus, $insertMessage);
			redirect("program/editProgram/{$programId}");
			
		}
		else{
			$this->editProgram($programId);
		}
	}

	public function updateProgramArea(){
		$programId = $this->input->post('programId');

		$programArea = $this->input->post('new_program_area');

		$programData = array('id_area'=>$programArea);

		
		$wasUpdated = $this->program_model->editProgram($programId, $programData);

		if($wasUpdated){
			$insertStatus = "success";
			$insertMessage = "Programa atualizado com sucesso!";
		}else{
			$insertStatus = "danger";
			$insertMessage = "Não foi possível atualizar os registros. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($insertStatus, $insertMessage);

		redirect('coordinator/coordinator_programs');

	}

	public function removeProgram($programId){

		
		$wasDeleted = $this->program_model->deleteProgram($programId);

		if($wasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Programa apagado com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível deletar o programa informado. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($deleteStatus, $deleteMessage);
		redirect('program');
	}

	public function registerNewProgram(){

		$this->load->model("auth/module_model");
		$foundGroup = $this->module_model->getGroupByGroupName(GroupConstants::COORDINATOR_GROUP);

		if($foundGroup !== FALSE){

			$this->load->module("auth/userController");
			$users = $this->usercontroller->getUsersOfGroup($foundGroup['id_group']);

			if($users !== FALSE){

				$usersForCoordinator = array();
				foreach($users as $user){
					$usersForCoordinator[$user['id']] = $user['name'];
				}
			}else{
				$usersForCoordinator = FALSE;
			}

		}else{
			$usersForCoordinator = FALSE;
		}

		$programArea = $this->getAllProgramAreas();

		$data = array(
			'users' => $usersForCoordinator,
			'programArea' => $programArea
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION, "program/program/new_program", $data);
	}

	public function newProgram(){

		$programDataIsOk = $this->validatesNewProgramData();

		if($programDataIsOk){

			$programName = $this->input->post('program_name');
			$programAcronym = $this->input->post('program_acronym');
			$programCoordinator = $this->input->post('program_coordinator');
			$openingYear = $this->input->post('opening_year');
			$programArea = $this->input->post('program_area');

			$programData = array(
				'program_name' => $programName,
				'acronym' => $programAcronym,
				'coordinator' => $programCoordinator,
				'opening_year' =>$openingYear,
				'id_area' => $programArea
			);

			$programNotExists = $this->verifyIfProgramNotExists($programName, $programAcronym);

						
			$session = getSession();
			if($programNotExists){
				$wasSaved = $this->program_model->saveProgram($programData);

				if($wasSaved){
					$insertStatus = "success";
					$insertMessage = "Programa cadastrado com sucesso!";
				}
				else{
					$insertStatus = "danger";
					$insertMessage = "Não foi possível cadastrar o programa. Tente novamente.";
				}

				$session->showFlashMessage($insertStatus, $insertMessage);
				redirect('program');
			}
			else{
				$insertStatus = "danger";
				$insertMessage = "Esse programa já está cadastrado.";
				$session->showFlashMessage($insertStatus, $insertMessage);
				redirect('program/registerNewProgram');
			}
		}
		else{

			$this->registerNewProgram();
		}
	}

	/**
	 * Validates the data submitted on the new program form
	 */
	private function validatesNewProgramData(){

		// form validation
		$this->load->library("form_validation");
		$this->form_validation->set_rules("program_name", "Nome do Programa", "required|trim");
		$this->form_validation->set_rules("program_acronym", "Sigla do Programa", "required|alpha");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$programDataStatus = $this->form_validation->run();

		return $programDataStatus;
	}

	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
	}

	private function verifyIfProgramNotExists($name, $acronym){

		$programNotExists = TRUE;

		$programs = $this->getAllPrograms();

		foreach ($programs as $program) {

			$nameExists = $name == $program['program_name'];

			$acronymExists = $acronym == $program['acronym'];

			if ($nameExists || $acronymExists){
				$programNotExists = FALSE;
				break;
			}
		}

		return $programNotExists;
	}

	// Verify if the name and acronym of the edited program already exists
	private function verifyTheNewData($id, $name, $acronym){

		$program = $this->getProgramById($id);

		$nameIsEqual = $name == $program['program_name'];
		$acronymIsEqual = $acronym == $program['acronym'];

		if($nameIsEqual && $acronymIsEqual){
			$dataIsOk = TRUE;
		}
		else{
			$dataIsOk = $this->verifyIfProgramNotExists($name, $acronym);
		}

		return $dataIsOk;
	}


	private function getProgramsWithInformation($allPrograms, $coursesPrograms){
		
		$id = 0;
		$programs = array();
		if($allPrograms !== FALSE){
			foreach($allPrograms as $program){
				$summaryNonExists = empty($program['summary']);
				$historyNonExists = empty($program['history']);
				$contactNonExists = empty($program['contact']);
				$researchLineNonExists = empty($program['research_line']);

				$coursesProgram = $coursesPrograms[$program['id_program']];
				$coursesNonExists = empty($coursesProgram);
				
				if(!$summaryNonExists || !$historyNonExists || !$contactNonExists || !$researchLineNonExists || !$coursesNonExists){
						$programs[$id] = $program;
						$id++;
				}
			}
		}
		else{
			$programs = FALSE;
		}

		return $programs;
	
	}

	public function getCoordinatorsForHomepage($programs){
		
		$this->load->model("program/coordinator_model");
		
		$coordinators = $this->coordinator_model->getCoordinatorsForHomepage($programs);
		
		return $coordinators;
	}

	private function getProgramsCoursesInfo($programs){

		$coursesProgram = array();

		if($programs !== FALSE){

			foreach ($programs as $program) {
				$coursesPrograms = $this->getProgramCourses($program['id_program']);	
				if ($coursesPrograms !== FALSE){
					$courses = $this->getProgramsCourses($coursesPrograms);
					$coursesProgram [$program['id_program']] = ($courses);

				}
				else{
					$coursesProgram[$program['id_program']] = FALSE;
				}
			}
		}

		return $coursesProgram;
	}

	private function getProgramsCourses($coursesPrograms){

		$i = 0;
		$courses = array();
		if($coursesPrograms !== FALSE){

			foreach ($coursesPrograms as $courses) {
				$coursesId[$i] = $courses['id_course'];
				$coursesName[$i] = $courses['course_name'];
				$i++;
			}

			$researchLines = $this->getCourseResearchLines($coursesId);
			$teachers = $this->getCourseTeachers($coursesId);
			$secretaries = $this->getCourseAcademicSecretarys($coursesId);

			$courses = array(
				'coursesId' => $coursesId,
				'coursesName' => $coursesName,
				'researchLines' => $researchLines,
				'teachers' => $teachers,
				'secretaries' => $secretaries
			);
		}
		
		return $courses;	
				
	}

	private function getCourseResearchLines($coursesId){

		$researchLines = array();
		if($coursesId !== FALSE){

			foreach ($coursesId as $id) {
							
				$this->load->module("program/course");
				$researchLine = $this->course->getCourseResearchLines($id);
				if(!empty($researchLine)){
					$researchLines[$id] = $researchLine;
				}
			}
		}

		return $researchLines;

	}

	private function getCourseTeachers($coursesId){

		$teachers = array();

		$this->load->module("program/teacher");
		if($coursesId !== FALSE){

			foreach ($coursesId as $id) {
				$teachers[$id] = $this->teacher->getCourseTeachersForHomepage($id);
			}
		}
		return $teachers;

	}

	private function getCourseAcademicSecretarys($coursesId){

		$secretariesInfo = array();
		
		if($coursesId !== FALSE){

			foreach ($coursesId as $id) {
							
				$this->load->model('program/course_model');
				$secretaries[$id] = $this->course_model->getAcademicSecretaryName($id);
			}

			if($secretaries !== FALSE){
				
				foreach ($secretaries as $secretary) {

					if(!empty($secretary)){

						$i = 0;
						foreach ($secretary as $secretaryInfo){
							$secretariesInfo[$i]['name'] = $secretaryInfo['name']; 
							$i++;
						}
					}

				}
			}
		}
		
		return $secretariesInfo;
	}
}
