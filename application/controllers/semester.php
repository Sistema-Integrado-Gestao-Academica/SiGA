<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/LoginException.php");

class Semester extends CI_Controller {

	public function getCurrentSemester(){

		$this->load->model('semester_model');

		$currentSemester = $this->semester_model->getCurrentSemester();

		return $currentSemester;
	}

	public function saveSemester() {
		
		$loggedUserData = $this->session->userdata('current_user');
		$loggedUserLogin = $loggedUserData['user']['login'];
		$password = $this->input->post('password');
		
		$this->load->model('usuarios_model');
		$this->load->model('semester_model');

		try{

			$user = $this->usuarios_model->validateUser($loggedUserLogin, $password);
 
			$accessGranted = sizeof($user) > 0;

			if($accessGranted){
				
				$semesterId = $this->input->post('current_semester_id') + 1;
				
				$wasUpdated = $this->semester_model->updateCurrentSemester($semesterId);

				if($wasUpdated){
					$this->session->set_flashdata("success", "Semestre atual alterado");
				}else{
					$this->session->set_flashdata("danger", "Não foi possível alterar o semestre atual.");
				}
				
				redirect('/usuario/secretary_offerList');
			}

		}catch(LoginException $caughtException){
			$this->session->set_flashdata("danger", "Falha na autenticação.");
			redirect('/usuario/secretary_offerList');
		}
	}

}
