<?php

require_once('syllabus.php');
require_once('offer.php');

class Discipline extends CI_Controller {

	//Function to load index page of disciplines
	public function discipline_index(){
		$this->load->template("discipline/index_discipline");
	}

	public function getClassesByDisciplineName($disciplineName, $offerId){

		$this->load->model('discipline_model');

		$disciplineClasses = $this->discipline_model->getClassesByDisciplineName($disciplineName, $offerId);

		return $disciplineClasses;
	}

	public function displayDisciplineClassesToEnroll($courseId, $disciplineId){

		$disciplineData = $this->getDisciplineByCode($disciplineId);

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offer = new Offer();
		$classes = $offer->getApprovedOfferListDisciplineClasses($courseId, $currentSemester['id_semester'], $disciplineId);

		$data = array(
			'courseId' => $courseId,
			'disciplineClasses' => $classes,
			'disciplineData' => $disciplineData
		);

		loadTemplateSafelyByGroup('estudante', 'discipline/discipline_classes_enroll', $data);
	}

	/**
	 * Function to get all disciplines registered in the database
	 * @return array $registeredDisciplines
	 */
	public function getAllDisciplines(){
		$this->load->model('discipline_model');
		$registeredDisciplines = $this->discipline_model->listAllDisciplines();

		return $registeredDisciplines;
	}

	public function getDisciplineByPartialName($disciplineName){

		$this->load->model('discipline_model');

		$disciplines = $this->discipline_model->getDisciplineByPartialName($disciplineName);

		return $disciplines;
	}

	public function getDisciplinesBySecretary($userId){

		$this->load->model('discipline_model');

		$disciplines = $this->discipline_model->getDisciplinesBySecretary($userId);

		return $disciplines;
	}

	public function getCourseSyllabusDisciplines($courseId){

		$this->load->model('discipline_model');

		$syllabus = new Syllabus();
		$foundSyllabus = $syllabus->getCourseSyllabus($courseId);

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

			$this->load->model('discipline_model');
			$alreadyExists = $this->discipline_model->disciplineExists($disciplineCode,$disciplineName);

			if($alreadyExists['code']){
				$this->session->set_flashdata("danger", "Código de disciplina já existe no sistema");
				redirect("discipline/discipline_index");
			}else if($alreadyExists['name']){
				$this->session->set_flashdata("danger", "Disciplina já existe no sistema");
				redirect("discipline/discipline_index");
			}else{
				$this->discipline_model->saveNewDiscipline($disciplineToRegister);
				$this->session->set_flashdata("success", "Disciplina \"{$disciplineName}\" cadastrada com sucesso");
				redirect("discipline/discipline_index");
			}
		}else{

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

			$this->load->model('discipline_model');
			$updated = $this->discipline_model->updateDisciplineData($disciplineCode,$disciplineToUpdate);
			$updateStatus = "success";
			$updateMessage = "Disciplina \"{$disciplineName}\" alterada com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Dados na forma incorreta.";
		}
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect('/discipline/discipline_index');
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

		$this->session->set_flashdata($deleteStatus, $deleteMessage);

		redirect('/discipline/discipline_index');
	}

	// Function to load a view form to edit a discipline
	public function formToEditDiscipline($code){
		$this->load->helper('url');
		$site_url = site_url();

		$this->load->model('discipline_model');
		$discipline_searched = $this->discipline_model->getDisciplineByCode($code);
		$data = array(
				'discipline' => $discipline_searched,
				'url' => $site_url
		);

		loadTemplateSafelyByPermission("discipline",'discipline/update_discipline', $data);

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

		loadTemplateSafelyByPermission("discipline", "discipline/register_discipline", array('courses'=>$coursesResult));
	}

	public function getDisciplineByCode($disciplineCode){

		$this->load->model('discipline_model');

		$discipline = $this->discipline_model->getDisciplineByCode($disciplineCode);

		return $discipline;
	}

	public function checkIfDisciplineExists($disciplineId){

		$this->load->model('discipline_model');

		$disciplineExists = $this->discipline_model->checkIfDisciplineExists($disciplineId);

		return $disciplineExists;
	}

	public function getDisciplineResearchLines($disciplineCode){

		$this->load->model('discipline_model');

		$disciplineResearchLines = $this->discipline_model->getDisciplineResearchLines($disciplineCode);

		return $disciplineResearchLines;
	}

	public function saveDisciplineResearchRelation($relationToSave){
		$this->load->model('discipline_model');

		$saved = $this->discipline_model->saveDisciplineResearchLine($relationToSave);
		return $saved;
	}

	public function deleteDisciplineResearchRelation($researchRelation){
		$this->load->model('discipline_model');

		$deleted = $this->discipline_model->deleteDisciplineResearchLine($researchRelation);
		return $deleted;

	}

	/**
	 * Function to drop a discipline from the database
	 * @param int $code - Code of the discipline
	 * @return boolean $deletedDiscipline
	 */
	private function dropDiscipline($code){
		$this->load->model('discipline_model');
		$deletedDiscipline = $this->discipline_model->deleteDiscipline($code);

		return $deletedDiscipline;
	}

	/**
	 * Validates the data submitted on the new discipline form
	 */
	private function validatesDisciplineFormsData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("discipline_name", "Discipline Name", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("discipline_code", "Discipline Code", "required");
		$this->form_validation->set_rules("name_abbreviation", "Name Abbreviation", "required|trim|xss_clean");
		$this->form_validation->set_rules("credits", "Credits", "required");

		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$courseDataStatus = $this->form_validation->run();

		return $courseDataStatus;
	}

	function alpha_dash_space($str){
		return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
	}
}