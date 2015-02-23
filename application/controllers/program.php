<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program extends CI_Controller {
	
	public function getAllPrograms(){

		$this->load->model('program_model');

		$programs = $this->program_model->getAllPrograms();

		return $programs;
	}

	public function registerNewProgram(){

		$usersForCoordinator = array();

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
