<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/secretary/constants/DocumentConstants.php");

class DocumentRequestStudent extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('student/documentrequeststudent_model', "doc_request_model");
	}

	public function index(){

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$this->load->model("auth/usuarios_model");
		$userCourse = $this->usuarios_model->getUserCourse($userId);

		$data = array(
			'userData' => $loggedUserData,
			'courses' => $userCourse
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "student/documentrequest/index", $data);
	}

	public function requestDocument($courseId, $userId){

		$studentRequests = $this->doc_request_model->getStudentsRequestOfCourse($userId, $courseId);

		$types = $this->doc_request_model->allNonDeclarationTypes();

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

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "student/documentrequest/request_document", $data);
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

			case DocumentConstants::TRANSFER_DOCS:
				break;

			case DocumentConstants::DECLARATIONS:

				$declarationType = $this->input->post('declarationType');

				$requestData = array(
					'id_student' =>	$studentId,
					'id_course' => $courseId,
					'document_type' => $declarationType,
					'status' => DocumentConstants::REQUEST_OPEN
				);

				$wasSaved = $this->saveDocumentRequest($requestData);

				if($wasSaved){
					$status = "success";
					$message = "Solicitação de documento enviada com sucesso.";
				}else{
					$status = "danger";
					$message = "Não foi possível enviar a solicitação de documento informada.";
				}

				$session = getSession();
				$session->showFlashMessage($status, $message);
				redirect("secretary/documentrequest/requestDocument/{$courseId}/{$studentId}");

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
				
				$session = getSession();
				$session->showFlashMessage($status, $message);
				redirect("secretary/documentrequest/requestDocument/{$courseId}/{$studentId}");

				break;

			default:
				break;
		}
	}

	private function saveDocumentRequest($documentRequestData){
		$wasSaved = $this->doc_request_model->saveDocumentRequest($documentRequestData);

		$docRequest = new DocumentConstants();
		$types = $docRequest->getAllTypes();
		$requestedDoc = $types[$documentRequestData['document_type']];

		$student = $documentRequestData["id_student"];
		$course = $documentRequestData["id_course"];

		$this->load->module("notification/notification");
		$this->notification->documentRequestNotification($student, $course, $requestedDoc);

		return $wasSaved;
	}

	public function cancelRequest($requestId, $courseId, $studentId){
	
		$wasDeleted = $this->doc_request_model->deleteRequest($requestId);

		if($wasDeleted){
			$status = "success";
			$message = "Solicitação de documento cancelada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível cancelar a solicitação de documento informada.";
		}

		$session = getSession();

		$session->showFlashMessage($status, $message);
		redirect("student/documentrequestStudent/requestDocument/{$courseId}/{$studentId}");
	}

	public function archiveRequest($requestId, $courseId, $studentId){

		$wasArchived = $this->doc_request_model->archiveRequest($requestId);

		if($wasArchived){
			$status = "success";
			$message = "Solicitação de documento arquivada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível arquivar a solicitação de documento informada.";
		}
		$session = getSession();

		$session->showFlashMessage($status, $message);
		redirect("student/documentrequestStudent/requestDocument/{$courseId}/{$studentId}");
	}

	public function displayArchivedRequests($courseId, $studentId){

		$archivedRequests = $this->doc_request_model->getStudentArchivedRequests($studentId, $courseId);

		$data = array(
			'archivedRequests' => $archivedRequests,
			'courseId' => $courseId,
			'studentId' => $studentId
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "student/documentrequest/archived_requests", $data);
	}

	// Functions to secretary //

	public function documentRequestSecretary(){

		$session = getSession();
		$currentUser = $session->getUserData();
		$userId = $currentUser->getId();

		$this->load->model("program/course_model");
		$courses = $this->course_model->getCoursesOfSecretary($userId);

		$data = array(
			'courses' => $courses,
			'userData' => $currentUser
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "student/documentrequest/doc_request", $data);
	}

	public function documentRequestReport($courseId){

		$courseRequests = $this->doc_request_model->getCourseRequests($courseId);

		$this->load->model("program/course_model");
		$courseData = $this->course_model->getCourseById($courseId);

		$users = $this->getUsersRequest($courseRequests);
		$data = array(
			'courseRequests' => $courseRequests,
			'courseData' => $courseData,
			'user' => $users
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "student/documentrequest/doc_request_report", $data);
	}

	public function documentReady($requestId, $courseId){

		$documentIsReady = $this->doc_request_model->setDocumentReady($requestId);

		if($documentIsReady){
			$status = "success";
			$message = "Status do documento atualizado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível atualizar o status do documento.";
		}
		$session = getSession();

		$session->showFlashMessage($status, $message);
		redirect("secretary_doc_requests/{$courseId}");
	}

	public function displayAnsweredRequests($courseId){

		$answeredRequests = $this->doc_request_model->getAnsweredRequests($courseId);

		$users = $this->getUsersRequest($answeredRequests);
		$data = array(
			'answeredRequests' => $answeredRequests,
			'courseId' => $courseId,
			'user' => $users
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "student/documentrequest/answered_requests", $data);
	}

	// Other methods

	public function allNonDeclarationTypes(){

		$types = $this->doc_request_model->allNonDeclarationTypes();

		return $types;
	}

	public function allDeclarationTypes(){

		$types = $this->doc_request_model->allDeclarationTypes();

		return $types;
	}

	private function getUsersRequest($requests){

		$users = array();
		if($requests !== FALSE){

			foreach ($requests as $request) {
				
				$requestId = $request['id_request'];
				$userId = $request['id_student'];
		
				$this->load->model("auth/usuarios_model");
				$users[$requestId] = $this->usuarios_model->getUserById($userId);
			}

		}
		else{
			$users = FALSE;
		}

		return $users;
	}
}
