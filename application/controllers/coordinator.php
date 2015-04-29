<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("program.php");

class Coordinator extends CI_Controller {

	public function index(){

		$loggedUser = $this->session->userdata("current_user");
		$user = $loggedUser['user'];
		$userId = $user['id'];

		$program = new Program();
		$coordinatorPrograms = $program->getCoordinatorPrograms($userId);

		$data = array(
			'coordinatorPrograms' => $coordinatorPrograms,
			'user' => $user
		);

		loadTemplateSafelyByGroup("coordenador",'program/coordinator_programs', $data);
	}
}