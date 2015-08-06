<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");
require_once("course.php");
require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/constants/DocumentConstants.php");

class DocumentRequest extends CI_Controller {

	// Functions to student //

	public function index(){

		$loggedUserData = $this->session->userdata("current_user");
		$userId = $loggedUserData['user']['id'];

		$user = new Usuario();
		$userCourse = $user->getUserCourses($userId);

		$data = array(
			'userData' => $loggedUserData['user'],
			'courses' => $userCourse
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "documentrequest/index", $data);		
	}

	public function requestDocument($courseId, $userId){

		$this->load->model('documentrequest_model', "doc_request_model");

		$studentRequests = $this->doc_request_model->getStudentsRequestOfCourse($userId, $courseId);

		$types = $this->doc_request_model->allDocumentTypes();

		if($types !== FALSE){
			foreach($types as $type){
				$documentTypes[$type['id_type']] = $type['document_type'];
			}
		}else{
			$documentTypes = FALSE;
		}

		$data = array(
			'documentTypes' => $documentTypes,
			'documentRequests' => $studentRequests,
			'courseId' => $courseId,
			'userId' => $userId
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "documentrequest/request_document", $data);
	}

	public function checkDocumentType(){

		$documentType = $this->input->post('documentType');

		switch($documentType){

			case DocumentConstants::QUALIFICATION_JURY:
				break;

			case DocumentConstants::DEFENSE_JURY:
				break;

			case DocumentConstants::PASSAGE_SOLICITATION:
				break;

			case DocumentConstants::TRANSCRIPT:
				break;

			case DocumentConstants::TRANSFER_DOCS:
				break;

			case DocumentConstants::SCHEDULE:
				break;

			case DocumentConstants::OTHER_DOCS:

				$otherDocument = array(
					"name" => "other_document_request",
					"id" => "other_document_request",
					"type" => "text",
					"class" => "form-campo form-control",
					"placeholder" => "Informe o nome do  documento desejado aqui.",
					"maxlength" => "50",
					'style' => "width:50%;",
					'required' => TRUE
				);

				$submitBtn = array(
					"id" => "request_document_btn",
					"class" => "btn bg-primary btn-flat",
					"content" => "Solicitar documento",
					"type" => "submit"
				);

				echo "<div class='form-group'>";
				echo form_label("Informe o documento desejado:", "other_document_request");
				echo form_input($otherDocument);
				echo "</div>";

				echo form_button($submitBtn);
				break;

			default:
				emptyDiv();
				break;
		}
	}

	public function newDocumentRequest(){

		$courseId = $this->input->post('courseId');
		$studentId = $this->input->post('studentId');

		$documentType = $this->input->post('documentType');

		switch($documentType){

			case DocumentConstants::QUALIFICATION_JURY:
				break;

			case DocumentConstants::DEFENSE_JURY:
				break;

			case DocumentConstants::PASSAGE_SOLICITATION:
				break;

			case DocumentConstants::TRANSCRIPT:
				break;

			case DocumentConstants::TRANSFER_DOCS:
				break;

			case DocumentConstants::SCHEDULE:
				break;

			case DocumentConstants::OTHER_DOCS:

				$otherDocumentName = $this->input->post('other_document_request');

				$requestData = array(
					'id_student' =>	$studentId,
					'id_course' => $courseId,
					'document_type' => $documentType,
					'status' => DocumentConstants::REQUEST_OPEN,
					'other_name' => $otherDocumentName
				);

				$wasSaved = $this->saveDocumentRequest($requestData);

				if($wasSaved){
					$status = "success";
					$message = "Solicitação de documento enviada com sucesso.";
				}else{
					$status = "danger";
					$message = "Não foi possível enviar a solicitação de documento informada.";
				}

				$this->session->set_flashdata($status, $message);
				redirect("documentrequest/requestDocument/{$courseId}/{$studentId}");

				break;

			default:
				break;
		}
	}

	private function saveDocumentRequest($documentRequestData){

		$this->load->model('documentrequest_model', "doc_request_model");

		$wasSaved = $this->doc_request_model->saveDocumentRequest($documentRequestData);

		return $wasSaved;
	}

	public function cancelRequest($requestId, $courseId, $studentId){

		$this->load->model('documentrequest_model', "doc_request_model");

		$wasDeleted = $this->doc_request_model->deleteRequest($requestId);

		if($wasDeleted){
			$status = "success";
			$message = "Solicitação de documento cancelada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível cancelar a solicitação de documento informada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("documentrequest/requestDocument/{$courseId}/{$studentId}");
	}

	// Functions to secretary //

	public function documentRequestSecretary(){

		$loggedUserData = $this->session->userdata("current_user");
		$currentUser = $loggedUserData['user'];
		$userId = $currentUser['id'];

		$course = new Course();
		$courses = $course->getCoursesOfSecretary($userId);

		$data = array(
			'courses' => $courses,
			'userData' => $currentUser
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "documentrequest/doc_request", $data);
	}

	public function documentRequestReport($courseId){

		$this->load->model('documentrequest_model', "doc_request_model");

		$courseRequests = $this->doc_request_model->getCourseRequests($courseId);

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'courseRequests' => $courseRequests,
			'courseData' => $courseData
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "documentrequest/doc_request_report", $data);		
	}

	public function documentReady($requestId, $courseId){

		$this->load->model('documentrequest_model', "doc_request_model");

		$documentIsReady = $this->doc_request_model->setDocumentReady($requestId);

		if($documentIsReady){
			$status = "success";
			$message = "Status do documento atualizado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível atualizar o status do documento.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("documentrequest/documentRequestReport/{$courseId}");
	}
}
