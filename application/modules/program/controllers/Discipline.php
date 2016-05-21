<?php

require_once(MODULESPATH."auth/constants/PermissionConstants.php");
require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."secretary/exception/DisciplineException.php");

class Discipline extends MX_Controller {

	const MODEL_NAME = "program/discipline_model";

	public function __construct(){
		parent::__construct();
		$this->load->model(self::MODEL_NAME);
	}

	//Function to load index page of disciplines
	public function discipline_index(){

		$this->load->module("auth/module");
		$userIsAdmin = $this->module->checkUserGroup(GroupConstants::ADMIN_GROUP);

		if ($userIsAdmin){
			$disciplines = $this->getAllDisciplines();
		}else{
			$session = getSession();
			$user = $session->getUserData();
			$userId = $user->getId();
			$disciplines = $this->getDisciplinesBySecretary($userId);
		}

		$data = array(
			'disciplines' => $disciplines,
			'userIsAdmin' => $userIsAdmin
		);

		loadTemplateSafelyByPermission(PermissionConstants::DISCIPLINE_PERMISSION, "program/discipline/index_discipline", $data);
	}

	public function getClassesByDisciplineName($disciplineName, $offerId){

		$disciplineClasses = $this->discipline_model->getClassesByDisciplineName($disciplineName, $offerId);

		return $disciplineClasses;
	}

	public function displayDisciplineClassesToEnroll($courseId, $disciplineId){

		$disciplineData = $this->getDisciplineByCode($disciplineId);

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		$offer = new Offer();
		$classes = $offer->getApprovedOfferListDisciplineClasses($courseId, $currentSemester['id_semester'], $disciplineId);

		$data = array(
			'courseId' => $courseId,
			'disciplineClasses' => $classes,
			'disciplineData' => $disciplineData
		);

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'program/discipline/discipline_classes_enroll', $data);
	}

	/**
	 * Function to get all disciplines registered in the database
	 * @return array $registeredDisciplines
	 */
	public function getAllDisciplines(){

		$registeredDisciplines = $this->discipline_model->listAllDisciplines();

		return $registeredDisciplines;
	}

	public function getDisciplineByPartialName($disciplineName){

		$disciplines = $this->discipline_model->getDisciplineByPartialName($disciplineName);

		return $disciplines;
	}

	public function getDisciplinesBySecretary($userId){

		$disciplines = $this->discipline_model->getDisciplinesBySecretary($userId);

		return $disciplines;
	}

	public function getCourseSyllabusDisciplines($courseId){

		$this->load->model("secretary/syllabus_model");
		$foundSyllabus = $this->syllabus_model->getCourseSyllabus($courseId);

		if(sizeof($foundSyllabus) > 0){

			$syllabusId = $foundSyllabus['id_syllabus'];
			$disciplines = $this->discipline_model->getCourseSyllabusDisciplines($syllabusId);
		}else{
			$disciplines = FALSE;
		}

		return $disciplines;
	}

	/**
	 * Function to save a new discipline
	 */
	public function newDiscipline(){

		$disciplineDataStatus = $this->validatesDisciplineFormsData();

		if($disciplineDataStatus){
			define('WORKLOAD_PER_CREDIT', 15);

			$disciplineName = $this->input->post('discipline_name');
			$disciplineCode = $this->input->post('discipline_code');
			$acronym 		 = $this->input->post('name_abbreviation');
			$credits		 = $this->input->post('credits');
			$disciplineCourse = $this->input->post('course_prolongs');
			$workload 		 = $credits * WORKLOAD_PER_CREDIT;

			$disciplineToRegister = array(
				'discipline_code'   => $disciplineCode,
				'discipline_name'   => $disciplineName,
				'name_abbreviation' => $acronym,
				'credits'			=> $credits,
				'workload' 		    => $workload,
				'id_course_discipline' => $disciplineCourse
			);


			$alreadyExists = $this->discipline_model->disciplineExists($disciplineCode,$disciplineName);

			$session = getSession();
			if($alreadyExists['code']){
				$session->showFlashMessage("danger", "Código de disciplina já existe no sistema");
				redirect("program/discipline/formToRegisterNewDiscipline");
			}else if($alreadyExists['name']){
				$session->showFlashMessage("danger", "Disciplina já existe no sistema");
				redirect("program/discipline/formToRegisterNewDiscipline");
			}else{
				$this->discipline_model->saveNewDiscipline($disciplineToRegister);
				$session->showFlashMessage("success", "Disciplina \"{$disciplineName}\" cadastrada com sucesso");
				redirect("program/discipline/discipline_index");
			}
		}
		else{
			$this->formToRegisterNewDiscipline();
		}

	}

	/**
	 * Function to update some discipline
	 */
	public function updateDiscipline(){
		$disciplineDataStatus = $this->validatesDisciplineFormsData();

		if($disciplineDataStatus){
			$disciplineName = $this->input->post('discipline_name');
			$disciplineCode = $this->input->post('discipline_code');
			$acronym 		 = $this->input->post('name_abbreviation');
			$credits		 = $this->input->post('credits');
			$workload 		 = $this->input->post('workload');

			$disciplineToUpdate = array(
					'discipline_name'   => $disciplineName,
					'name_abbreviation' => $acronym,
					'credits'			=> $credits,
					'workload' 		    => $workload
			);

			try{

				$updated = $this->discipline_model->updateDisciplineData($disciplineCode,$disciplineToUpdate);
				
				$updateStatus = "success";
				$updateMessage = "Disciplina \"{$disciplineName}\" alterada com sucesso";
			}catch(DisciplineException $e){
				$updateStatus = "danger";
				$updateMessage = $e->getMessage();
			}
		}else{
			$updateStatus = "danger";
			$updateMessage = "Dados na forma incorreta.";
		}

		$session = getSession();
		$session->showFlashMessage($updateStatus, $updateMessage);
		redirect('/program/discipline/discipline_index');
	}

	/**
	 * Function to delete some discipline resgistered
	 */
	public function deleteDiscipline(){
		$discipline_code = $this->input->post('discipline_code');
		$disciplineWasDeleted = $this->dropDiscipline($discipline_code);

		if($disciplineWasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Disciplina excluída com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível excluir esta disciplina.";
		}

		$session = getSession();
		$session->showFlashMessage($deleteStatus, $deleteMessage);

		redirect('/program/discipline/discipline_index');
	}

	// Function to load a view form to edit a discipline
	public function formToEditDiscipline($code){
		$this->load->helper('url');
		$site_url = site_url();


		$discipline_searched = $this->discipline_model->getDisciplineByCode($code);
		$data = array(
				'discipline' => $discipline_searched,
				'url' => $site_url
		);

		loadTemplateSafelyByPermission(PermissionConstants::DISCIPLINE_PERMISSION,'program/discipline/update_discipline', $data);

	}

	// Function to load a view form to register a discipline
	public function formToRegisterNewDiscipline(){
		$this->load->model('course_model');

		$courses = $this->course_model->getAllCourses();
		if($courses !== FALSE){
			foreach ($courses as $course){

				$coursesResult[$course['id_course']] = $course['course_name'];
			}
		}else{
			$coursesResult = FALSE;
		}

		loadTemplateSafelyByPermission(PermissionConstants::DISCIPLINE_PERMISSION, "program/discipline/register_discipline", array('courses'=>$coursesResult));
	}

	public function getDisciplineByCode($disciplineCode){

		$discipline = $this->discipline_model->getDisciplineByCode($disciplineCode);

		return $discipline;
	}

	public function checkIfDisciplineExists($disciplineId){

		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($disciplineId);

		return $disciplineExists;
	}

	public function getDisciplineResearchLines($disciplineCode){

		$disciplineResearchLines = $this->discipline_model->getDisciplineResearchLines($disciplineCode);

		return $disciplineResearchLines;
	}

	public function saveDisciplineResearchRelation($relationToSave){

		$saved = $this->discipline_model->saveDisciplineResearchLine($relationToSave);
		return $saved;
	}

	public function deleteDisciplineResearchRelation($researchRelation){

		$deleted = $this->discipline_model->deleteDisciplineResearchLine($researchRelation);
		return $deleted;

	}

	/**
	 * Function to drop a discipline from the database
	 * @param int $code - Code of the discipline
	 * @return boolean $deletedDiscipline
	 */
	private function dropDiscipline($code){

		$deletedDiscipline = $this->discipline_model->deleteDiscipline($code);

		return $deletedDiscipline;
	}

	/**
	 * Validates the data submitted on the new discipline form
	 */
	private function validatesDisciplineFormsData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("discipline_name", "Discipline Name", "required|trim");
		$this->form_validation->set_rules("discipline_code", "Discipline Code", "required");
		$this->form_validation->set_rules("name_abbreviation", "Name Abbreviation", "required|trim");
		$this->form_validation->set_rules("credits", "Credits", "required");

		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}
}