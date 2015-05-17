<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('program.php');

class Coordinator extends CI_Controller {

	private $COORDINATOR_GROUP = "coordenador";

	public function index() {
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/coordinator_home");
	}

	public function coordinator_programs(){

		$session = $this->session->userdata("current_user");
		$userData = $session['user'];
		$coordinatorId = $userData['id'];

		$program = new Program();
		$coordinatorPrograms = $program->getCoordinatorPrograms($coordinatorId);
		
		$data = array(
			'coordinatorPrograms' => $coordinatorPrograms,
			'userData' => $userData
		);

		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "coordinator/coordinator_programs", $data);
	}

	public function program_evaluation_index($programId){

		$program = new Program();

		$programData = $program->getProgramById($programId);

		$data = array(
			'programData' => $programData
		);
		
		loadTemplateSafelyByGroup($this->COORDINATOR_GROUP, "program/program_evaluation_index", $data);
	}

}
