<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."program/domain/intellectual_production/ProductionType.php");
require_once(MODULESPATH."program/domain/intellectual_production/EventPresentation.php");
require_once(MODULESPATH."program/domain/intellectual_production/Intellectualproduction.php");
require_once(MODULESPATH."program/exception/IntellectualProductionException.php");

class Production extends MX_Controller {

	public function __construct(){
		$this->load->model("program/production_model");
		$this->groups = array(GroupConstants::TEACHER_GROUP, GroupConstants::STUDENT_GROUP);
	}

	public function index(){

		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();

		$this->load->model("program/project_model");

		$intellectualProductions = $this->production_model->getUserProductions($userId);
		$eventPresentations = $this->production_model->getEventPresentations($userId);
		$eventParticipations = $this->production_model->getEventParticipations($userId);
		$projects = $this->project_model->getProjects($userId);

		$data = array(

			'types' => ProductionType::getTypes(),
			'subtypes' => ProductionType::getSubtypes(),
			'eventNatures' => EventPresentation::getEventNatures(),
			'presentationNatures' => EventPresentation::getPresentationNatures(),
			'intellectualProductions' => $intellectualProductions,
			'eventParticipations' => $eventParticipations,
			'eventPresentations' => $eventPresentations,
			'projects' => makeDropdownArray($projects, 'id', 'name'),
			'user' => $user
		);

		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/productions", $data);
	}

	public function save(){

		$production = $this->getProductionData();

		if($production !== FALSE){

			$success = $this->production_model->createProduction($production);
			$session = getSession();
			if($success){
				$session->showFlashMessage("success", "Produção intelectual adicionada com sucesso!");
				$this->addCoauthors($production);
			}
			else{
				$session->showFlashMessage("danger", "Não foi possível adicionar a produção intelectual");
				$this->index();
			}
		}
		else{
			$this->index();
		}

	}

	public function addCoauthors($production){

		$productionId = $this->production_model->getLastProduction($production);
		$this->loadPageCoauthors($productionId, "new_coauthors");
	}

	public function editCoauthors($productionId){
		$this->loadPageCoauthors($productionId, "edit_coauthors");
	}

	private function loadPageCoauthors($productionId, $file){

		$session = getSession();
		$user = $session->getUserData();

		$authors = $this->production_model->getAuthorsByProductionId($productionId);

		$data = array(
			'productionId' => $productionId,
			'author' => $user,
			'authors' => $authors
		);

		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/{$file}", $data);
	}

	public function editCoauthor($productionId, $order){

		$author = $this->production_model->getAuthorByProductionAndOrder($productionId, $order);

		$data = array(
			'productionId' => $productionId,
			'author' => $author[0],
		);

		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/edit_coauthor", $data);
	}

	public function updateCoauthor($productionId, $order){

		$name = $this->input->post("name");
        $cpf = $this->input->post("cpf");
        $newOrder = $this->input->post("order");

		$this->load->model("auth/usuarios_model");
		$user = $this->usuarios_model->getUserByCpf($cpf);

        $id = FALSE;
		if($user !== FALSE){
			$id = $user['id'];
		}
        $author = new User($id, $name, $cpf);

		if($order !== $newOrder){

        	$exists = $this->production_model->checkIfOrderExists($newOrder, $productionId);
		}
		else{
			$exists = FALSE;
		}
		$session = getSession();

		if($exists){
			$session->showFlashMessage("danger", "Coautor existente na ordem informada");
			redirect("edit_coauthor/{$productionId}/{$order}");
		}
		else{

	        $data = array (
		        'cpf' => $cpf,
		        'author_name' => $name,
		        'order' => $newOrder,
		        'production_id' => $productionId,
		        'user_id' => $id
	    	);

			$success = $this->production_model->updateCoauthor($productionId, $order, $data);

			if($success){
				$session->showFlashMessage("success", "Autor editado com sucesso!");
				redirect("edit_coauthors/{$productionId}");

			}
			else{
				$session->showFlashMessage("danger", "Não foi possível editar o autor.");
				redirect("edit_coauthor/{$productionId}/{$order}");
			}

		}
	}

	public function edit($productionId){

		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();
		$production = $this->production_model->getProductionById($productionId);

		$this->load->model("program/project_model");
		$projects = $this->project_model->getProjects($userId);

		$data = array(
			'production' => $production,
			'types' => ProductionType::getTypes(),
			'subtypes' => ProductionType::getSubtypes(),
			'productionId' => $productionId,
			'projects' => makeDropdownArray($projects, 'id', 'name'),
			'author' => $user
		);

		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/edit_intellectual_production", $data);
	}

	public function update(){

		$productionId = $this->input->post('id');
		$production = $this->getProductionData($productionId);

		if($production !== FALSE){

			$success = $this->production_model->updateProduction($production);
			$session = getSession();

			if($success){
				$session->showFlashMessage("success", "Produção intelectual editada com sucesso!");
			}
			else{
				$session->showFlashMessage("danger", "Não foi possível editar a produção intelectual");
			}
			$this->index();
		}
		else{
			$this->index();
		}
	}

	public function delete(){

		$productionId = $this->input->post('id');
		$success = $this->production_model->deleteProduction($productionId);
		$session = getSession();

		if($success){
			$session->showFlashMessage("success", "Produção intelectual removida com sucesso!");
		}
		else{
			$session->showFlashMessage("danger", "Não foi possível remover a produção intelectual");
		}
		$this->index();
	}

	private function getProductionData($productionId = FALSE){

		$valid = $this->validateProductionData();

		if($valid){

			$title = $this->input->post("title");
			$year = $this->input->post("year");
			$type = $this->input->post("types");
			$subtype = $this->input->post("subtypes");
			$periodic = $this->input->post("periodic");
			$qualis = $this->input->post("qualis");
			$identifier = $this->input->post("identifier");
			$project = $this->input->post("projects");

			$session = getSession();
			$user = $session->getUserData();
			$author = $user->getId();

			try{

				$production = new IntellectualProduction($author, $title, $type, $year, $subtype,
															$qualis, $periodic, $identifier, $productionId, FALSE, $project);
			}
			catch(IntellectualProductionException $exception){
				$production = FALSE;
				$session->showFlashMessage("danger", $exception->getMessage());
			}
		}
		else{
			$production = FALSE;
		}

		return $production;
	}

	private function validateProductionData(){

		// form validation
		$this->load->library("form_validation");

		$this->form_validation->set_rules("title", "Título da produção", "required|trim");
		$this->form_validation->set_rules("projects", "Projeto relacionado", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$valid = $this->form_validation->run();

		return $valid;
	}

	public function saveEventParticipation(){
		$eventData = $this->getEventData();
		$eventNatures = EventPresentation::getEventNatures();
		$session = getSession();
		if($eventData !== FALSE){
			
			$eventNatureId = $eventData['event_nature'];
			$eventData['event_nature'] = $eventNatures[$eventNatureId];
			$user = $session->getUserData()->getId();
			$eventData['student'] = $user;
			
			$saved = $this->production_model->saveStudentEvent($eventData);
			if($saved){
				$event = $eventData['event_name'];
				$status = "success";
				$message = "Participação no evento <b>{$event}</b> adicionada com sucesso.";
				$session->showFlashMessage($status, $message);
			}
			else{
				$status = "danger";
				$message = "Não foi possível adicionar a participação no evento <b>{$event}</b>. Tente novamente.";
				$session->showFlashMessage($status, $message);	
			}
		}
		else{
			$status = "danger";
			$message = "Preencha o período corretamente.";
			$session->showFlashMessage($status, $message);
		}
		$this->index();
	}

	public function saveEventPresentation(){
		$eventData = $this->getEventData();
		$session = getSession();
		if($eventData !== FALSE){
			$eventNatures = EventPresentation::getEventNatures();
			$eventNatureId = $eventData['event_nature'];
			$eventData['event_nature'] = $eventNatures[$eventNatureId];
			$user = $session->getUserData()->getId();
			$eventData['student'] = $user;
			
			$eventData['study_title'] = $this->input->post("title");
			$presentationNatureId = $this->input->post("presentation_nature");
			$presentationNatures = EventPresentation::getPresentationNatures();
			$eventData['presentation_nature'] = $presentationNatures[$presentationNatureId];
			
			$saved = $this->production_model->saveStudentEvent($eventData);
			if($saved){
				$event = $eventData['event_name'];
				$status = "success";
				$message = "Apresentação no evento <b>{$event}</b> adicionada com sucesso.";
				$session->showFlashMessage($status, $message);
			}
			else{
				$status = "danger";
				$message = "Não foi possível adicionar a apresentação no evento <b>{$event}</b>. Tente novamente.";
				$session->showFlashMessage($status, $message);	
			}
		}
		else{
			$status = "danger";
			$message = "Preencha o período corretamente.";
			$session->showFlashMessage($status, $message);
		}
		$this->index();
	}

	private function getEventData(){
		$eventName = $this->input->post("event_name");
		$eventNatureId = $this->input->post("event_nature");
		$place = $this->input->post("place");
		$startDate = $this->input->post("start_date");
		$endDate = $this->input->post("end_date");
		$promotingInstitution = $this->input->post("promoting_institution");

		if(!empty($startDate) && !empty($endDate)){
			$this->load->library("form_validation");

			$this->form_validation->set_rules("start_date", "Período de realização", "valid_period_date");
			$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
			$validDates = $this->form_validation->run();
		}
		elseif (!empty($startDate) && empty($endDate)) {
			$validDates = FALSE;
		}
		elseif (empty($startDate) && !empty($endDate)) {
			$validDates = FALSE;
		}
		else{
			$validDates = TRUE;
		}

		if($validDates){
			$eventData = array(
				'event_name' => $eventName,
				'event_nature' => $eventNatureId,
				'place' => $place,
				'start_date' => convertDateToDateTime($startDate),
				'end_date' => convertDateToDateTime($endDate),
				'promoting_institution' => $promotingInstitution
			);
		}
		else{
			$eventData = FALSE;
		}

		return $eventData;
	}

	public function editEventPresentation($eventPresentationId){
		
		$data = $this->getEventProductionData($eventPresentationId);
		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/edit_event_presentation", $data);
	}

	public function editEventParticipation($eventParticipationId){

		$data = $this->getEventProductionData($eventParticipationId);
		loadTemplateSafelyByGroup($this->groups, "program/intellectual_production/edit_event_participation", $data);
	}

	private function getEventProductionData($eventProductionId){
		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();
		$production = $this->production_model->getEventProductionById($eventProductionId);

		$data = array(
			'eventProduction' => $production,
			'eventNatures' => EventPresentation::getEventNatures(),
			'presentationNatures' => EventPresentation::getPresentationNatures()
		);
	
		return $data;		
	}

	public function updateEventPresentation(){

		$eventProductionId = $this->input->post('id');
		$data = $this->getEventData();

		$session = getSession();
		if($data !== FALSE){
			$eventNatureId = $data['event_nature'];
			$eventNatures = EventPresentation::getEventNatures();
			$data['event_nature'] = $eventNatures[$eventNatureId];

			$data['study_title'] = $this->input->post("title");
			$presentationNatureId = $this->input->post("presentation_nature");
			$presentationNatures = EventPresentation::getPresentationNatures();
			$data['presentation_nature'] = $presentationNatures[$presentationNatureId];
			
			$success = $this->production_model->updateEventProduction($data, $eventProductionId);

			if($success){
				$session->showFlashMessage("success", "Apresentação de trabalho editada com sucesso!");
			}
			else{
				$session->showFlashMessage("danger", "Não foi possível editar a apresentação de trabalho");
			}
		}
		else{
			$session->showFlashMessage("danger", "Preencha o período corretamente");
		}
		$this->index();
	}

	public function updateEventParticipation(){

		$eventProductionId = $this->input->post('id');
		$data = $this->getEventData();

		$session = getSession();
		if($data !== FALSE){
			$eventNatureId = $data['event_nature'];
			$eventNatures = EventPresentation::getEventNatures();
			$data['event_nature'] = $eventNatures[$eventNatureId];


			$success = $this->production_model->updateEventProduction($data, $eventProductionId);

			if($success){
				$session->showFlashMessage("success", "Participação em evento editada com sucesso!");
			}
			else{
				$session->showFlashMessage("danger", "Não foi possível editar a participação em evento");
			}
		}
		else{
			$session->showFlashMessage("danger", "Preencha o período corretamente");
		}
		$this->index();
	}

	public function deleteEventPresentation(){

		$success = $this->deleteEventProduction();
		$session = getSession();

		if($success){
			$session->showFlashMessage("success", "Apresentação de trabalho removida com sucesso!");
		}
		else{
			$session->showFlashMessage("danger", "Não foi possível remover a apresentação de trabalho ");
		}
		$this->index();
	}

	public function deleteEventParticipation(){

		$success = $this->deleteEventProduction();
		$session = getSession();

		if($success){
			$session->showFlashMessage("success", "Participação em evento removida com sucesso!");
		}
		else{
			$session->showFlashMessage("danger", "Não foi possível remover a participação em evento");
		}
		$this->index();
	}

	private function deleteEventProduction(){

		$eventProductionId = $this->input->post('id');
		$deleted = $this->production_model->deleteEventProduction($eventProductionId);

		return $deleted;
	}
}