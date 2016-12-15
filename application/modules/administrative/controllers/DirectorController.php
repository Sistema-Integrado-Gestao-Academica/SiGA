<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class DirectorController extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('administrative/director_model');
	}

	public function defineDirector(){
		$this->load->model('program/teacher_model');
		$teachers = $this->teacher_model->getAllTeachers();
		$teachers = makeDropdownArray($teachers, 'id', 'name');

		$currentDirector = $this->director_model->getCurrentDirector();

		$data = array(
			'teachers' => $teachers,
			'currentDirector' => $currentDirector
		);

		$permittedGroups = array(GroupConstants::DIRECTOR_GROUP, GroupConstants::ADMIN_GROUP);
		loadTemplateSafelyByGroup($permittedGroups,'administrative/director/define', $data);
	}

	public function saveDirector(){

		$director = $this->input->post("new_director");
		$saved = $this->director_model->insertUserOnDirectorGroup($director);

		$session = getSession();

		if($saved){
			$status = 'success';
			$message = 'Diretor definido com sucesso.';
			$session->showFlashMessage($status, $message);
			redirect('/');
		}
		else{
			$status = 'danger';
			$message = 'Não foi possível definir o diretor. Tente novamente.';
			$session->showFlashMessage($status, $message);
			redirect('define_director');
		}
	}
}
