<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."program/domain/intellectual_production/ProductionType.php");
require_once(MODULESPATH."program/domain/intellectual_production/Intellectualproduction.php");
require_once(MODULESPATH."program/exception/IntellectualProductionException.php");

class Production extends MX_Controller {

	public function __construct(){
		$this->load->model("program/production_model");
	}


	public function index(){

		$groups = array(GroupConstants::TEACHER_GROUP, GroupConstants::STUDENT_GROUP);

		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();

		$productions = $this->production_model->getUserProductions($userId);
		$data = array(

			'types' => ProductionType::getTypes(),
			'subtypes' => ProductionType::getSubtypes(),
			'productions' => $productions
		);

		loadTemplateSafelyByGroup($groups, "program/intellectual_production/intellectual_production", $data);

	}

	public function save(){

		$valid = $this->validateProductionData();

		if($valid){

			$title = $this->input->post("title");
			$year = $this->input->post("year");
			$type = $this->input->post("types");
			$subtype = $this->input->post("subtypes");
			$periodic = $this->input->post("periodic");
			$qualis = $this->input->post("qualis");
			$identifier = $this->input->post("identifier");

			$session = getSession();
			$user = $session->getUserData();
			$author = $user->getId();

			try{
				
				$production = new IntellectualProduction($author, $title, $type, $year, $subtype,
															$qualis, $periodic, $identifier);
				$success = $this->production_model->createProduction($production);
				
				if($success){
					$session->showFlashMessage("success", "Produção intelectual adicionada com sucesso!");
				}
				else{
					$session->showFlashMessage("danger", "Não foi possível adicionar a produção intelectual");
				}
			}
			catch(IntellectualProductionException $exception){
				$session->showFlashMessage("danger", $exception->getMessage());
			}
			$this->index();

		}
		else{
			$this->index();
		}


	}

	private function validateProductionData(){

		// form validation
		$this->load->library("form_validation");
		
		$this->form_validation->set_rules("title", "Título da produção", "required|trim");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$valid = $this->form_validation->run();

		return $valid;
	}


}