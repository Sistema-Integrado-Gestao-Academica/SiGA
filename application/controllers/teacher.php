<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/GroupConstants.php");

class Teacher extends CI_Controller {

	public function updateProfile(){
		
		$session = $this->session->userdata("current_user");
		$teacher = $session['user']['id'];

		$data = array(
			'teacher' => $teacher,
			'session' => $session
		);

		loadTemplateSafelyByGroup(GroupConstants::TEACHER_GROUP, 'teacher/update_profile', $data);
	}

	public function saveProfile(){
		
		$teacherId = $this->input->post('teacher');
		
		//$teacherDataIsOk = $this->validatesNewProgramData();

		//if($teacherDataIsOk){

			$summary = $this->input->post('summary');
			$lattes = $this->input->post('lattes');
			
			$teacherData = array(
				'summary' => $summary,
				'lattes' => $lattes
			);

			$this->load->model('teacher_model');

			$wasUpdated = $this->teacher_model->updateProfile($teacherId, $teacherData);

			if($wasUpdated){
				$insertStatus = "success";
				$insertMessage = "Perfil atualizado com sucesso!";
			}
			else{
				$insertStatus = "danger";
				$insertMessage = "Não foi possível atualizar o perfil. Tente novamente.";
			}

			$this->session->set_flashdata($insertStatus, $insertMessage);
			redirect('mastermind_home');
	}
}