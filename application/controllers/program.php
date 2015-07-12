<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('usuario.php');
require_once('module.php');
require_once('course.php');

class Program extends CI_Controller {

	public function getAllPrograms(){

		$this->load->model('program_model');

		$programs = $this->program_model->getAllPrograms();

		return $programs;
	}

	public function getCoordinatorPrograms($coordinatorId){

		$this->load->model('program_model');

		$programs = $this->program_model->getCoordinatorPrograms($coordinatorId);

		return $programs;	
	}

	public function getProgramEvaluations($programId){

		$this->load->model('program_model');

		$programEvaluations = $this->program_model->getProgramEvaluations($programId);

		return $programEvaluations;	
	}

	public function getProgramEvaluation($programEvaluationId){

		$this->load->model('program_model');

		$programEvaluation = $this->program_model->getProgramEvaluation($programEvaluationId);

		return $programEvaluation;
	}

	public function getProgramById($programId){

		$this->load->model('program_model');

		$programs = $this->program_model->getProgramById($programId);

		return $programs;	

	}

	public function getProgramCourses($programId){

		$this->load->model('program_model');

		$programCourses = $this->program_model->getProgramCourses($programId);

		return $programCourses;
	}

	public function addCourseToProgram($courseId, $programId){

		$this->load->model('program_model');

		$wasAdded = $this->program_model->addCourseToProgram($courseId, $programId);

		if($wasAdded){
			$insertStatus = "success";
			$insertMessage = "Curso adicionado com sucesso ao programa.";
		}else{
			$insertStatus = "danger";
			$insertMessage = "Não foi possível adicionar o curso informado.";
		}

		$this->session->set_flashdata($insertStatus, $insertMessage);
		redirect("program/editProgram/{$programId}");
	}

	public function removeCourseFromProgram($courseId, $programId){

		$this->load->model('program_model');

		$wasRemoved = $this->program_model->removeCourseFromProgram($courseId, $programId);

		if($wasRemoved){
			$removeStatus = "success";
			$removeMessage = "Curso removido com sucesso do programa.";
		}else{
			$removeStatus = "danger";
			$removeMessage = "Não foi possível adicionar o curso informado.";
		}

		$this->session->set_flashdata($removeStatus, $removeMessage);
		redirect("program/editProgram/{$programId}");
	}

	public function editProgram($programId){

		$this->load->model('program_model');
		$program = $this->program_model->getProgramById($programId);
		
		define("COORDINATOR_GROUP", "coordenador");

		$group = new Module();
		$foundGroup = $group->getGroupByName(COORDINATOR_GROUP);

		if($foundGroup !== FALSE){

			$user = new Usuario();
			$users = $user->getUsersOfGroup($foundGroup['id_group']);

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

		$course = new Course();

		$courses = $course->getCoursesToProgram($programId);

		$data = array(
			'programData' => $program,
			'users' => $usersForCoordinator,
			'courses' => $courses
		);

		loadTemplateSafelyByPermission('cursos', "program/edit_program", $data);
	}

	public function updateProgram(){

		$programId = $this->input->post('programId');
		
		$programDataIsOk = $this->validatesNewProgramData();

		if($programDataIsOk){

			$programName = $this->input->post('program_name');
			$programAcronym = $this->input->post('program_acronym');
			$programCoordinator = $this->input->post('program_coordinator');
			$openingYear = $this->input->post('opening_year');

			$programData = array(
				'program_name' => $programName,
				'acronym' => $programAcronym,
				'coordinator' => $programCoordinator,
				'opening_year' => $openingYear
			);

			$this->load->model('program_model');

			$wasUpdated = $this->program_model->editProgram($programId, $programData);

			if($wasUpdated){
				$insertStatus = "success";
				$insertMessage = "Programa atualizado com sucesso!";
			}else{
				$insertStatus = "danger";
				$insertMessage = "Não foi possível atualizar os registros. Tente novamente.";
			}

			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('cursos');

		}else{
			$insertStatus = "danger";
			$insertMessage = "Dados na forma incorreta. Cheque os dados informados. Espaços em branco não são aceitos.";
			
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect("program/editProgram/{$programId}");
		}
	}

	public function removeProgram($programId){

		$this->load->model('program_model');

		$wasDeleted = $this->program_model->deleteProgram($programId);

		if($wasDeleted){
			$deleteStatus = "success";
			$deleteMessage = "Programa apagado com sucesso.";
		}else{
			$deleteStatus = "danger";
			$deleteMessage = "Não foi possível deletar o programa informado. Tente novamente.";
		}

		$this->session->set_flashdata($deleteStatus, $deleteMessage);
		redirect('cursos');
	}

	public function registerNewProgram(){

		define("COORDINATOR_GROUP", "coordenador");

		$group = new Module();
		$foundGroup = $group->getGroupByName(COORDINATOR_GROUP);

		if($foundGroup !== FALSE){

			$user = new Usuario();
			$users = $user->getUsersOfGroup($foundGroup['id_group']);

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
		
		$data = array(
			'users' => $usersForCoordinator
		);

		loadTemplateSafelyByPermission('cursos', "program/new_program", $data);
	}

	public function newProgram(){

		$programDataIsOk = $this->validatesNewProgramData();

		if($programDataIsOk){

			$programName = $this->input->post('program_name');
			$programAcronym = $this->input->post('program_acronym');
			$programCoordinator = $this->input->post('program_coordinator');
			$openingYear = $this->input->post('opening_year');

			$programData = array(
				'program_name' => $programName,
				'acronym' => $programAcronym,
				'coordinator' => $programCoordinator,
				'opening_year' =>$openingYear
			);

			$this->load->model('program_model');
			
			$wasSaved = $this->program_model->saveProgram($programData);

			if($wasSaved){
				$insertStatus = "success";
				$insertMessage = "Programa cadastrado com sucesso!";
			}else{
				$insertStatus = "danger";
				$insertMessage = "Não foi possível cadastrar o programa. Tente novamente.";
			}

			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('cursos');

		}else{

			$insertStatus = "danger";
			$insertMessage = "Dados na forma incorreta.";
			
			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('program/registerNewProgram');
		}
	}

	/**
	 * Validates the data submitted on the new program form
	 */
	private function validatesNewProgramData(){
		$this->load->library("form_validation");
		$this->form_validation->set_rules("program_name", "Nome do Programa", "required|trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("program_acronym", "Sigla do Programa", "required|alpha");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$programDataStatus = $this->form_validation->run();

		return $programDataStatus;
	}

	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
	}
}
