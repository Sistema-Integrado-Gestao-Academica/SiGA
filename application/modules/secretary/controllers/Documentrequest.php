<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/secretary/constants/DocumentConstants.php");

class DocumentRequest extends MX_Controller {

	const DOC_NAME_PREFIX = "document_";
	const DOCS_UPLOAD_PATH = "upload_files/docrequests";

	public function __construct(){
		parent::__construct();
		$this->load->model('secretary/documentrequest_model', "doc_request_model");
	}

	public function index(){

		$session = getSession();
		$loggedUserData = $session->getUserData();
		$userId = $loggedUserData->getId();

		$this->load->model("auth/usuarios_model");
		$userCourse = $this->usuarios_model->getUserCourses($userId);

		$data = array(
			'userData' => $loggedUserData,
			'courses' => $userCourse
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "secretary/documentrequest/index", $data);
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

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "secretary/documentrequest/request_document", $data);
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
		redirect("secretary/documentrequest/requestDocument/{$courseId}/{$studentId}");
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
		redirect("secretary/documentrequest/requestDocument/{$courseId}/{$studentId}");
	}

	public function displayArchivedRequests($courseId, $studentId){

		$archivedRequests = $this->doc_request_model->getStudentArchivedRequests($studentId, $courseId);

		$data = array(
			'archivedRequests' => $archivedRequests,
			'courseId' => $courseId,
			'studentId' => $studentId
		);

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_PERMISSION, "secretary/documentrequest/archived_requests", $data);
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

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "secretary/documentrequest/doc_request", $data);
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

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "secretary/documentrequest/doc_request_report", $data);
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

		loadTemplateSafelyByPermission(PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION, "secretary/documentrequest/answered_requests", $data);
	}

	private function uploadOptions($fileName, $requestId){

		$path = APPPATH.self::DOCS_UPLOAD_PATH;

		$path = $this->createFolders($path, $requestId);

		$config['upload_path'] = $path;
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'pdf|png|jpg|jpeg';
        $config['max_size'] = '6000';
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = TRUE;

        return $config;
	}

	private function createFolders($path, $requestId){

		// Create the folder to the request if it not exists
		if(!is_dir($path)){
			mkdir($path, 0755, TRUE);
		}

		$pathToAdd = "r".$requestId;		
		$newPath = $path."/".$pathToAdd;

		// Create the new path if it not exists
		if(!is_dir($newPath)){
			mkdir($newPath, 0755, TRUE);
		}
		
		return $newPath;
	}

	public function provideOnline(){
		
		$this->load->library('upload');

		$courseId = $this->input->post("course");
		$requestId = $this->input->post("request");

		$fileName = self::DOC_NAME_PREFIX.$requestId;

		$config = $this->uploadOptions($fileName, $requestId);

        $this->upload->initialize($config);

        if($this->upload->do_upload("requested_doc")){

            $doc = $this->upload->data();
            $docPath = $doc['full_path'];

            $wasUpdated = $this->doc_request_model->updateDocFile($requestId, $docPath);
            $documentIsReady = $this->doc_request_model->setDocumentReady($requestId, DocumentConstants::REQUEST_READY_ONLINE);

            $this->load->module("notification/notification");
            $this->notification->secretaryOnlineDocRequestNotification($requestId);

            if($wasUpdated && $documentIsReady){
                $status = "success";
                $message = "Documento salvo e expedido com sucesso!";
            }else{
                $status = "danger";
                $message = "Não foi possível salvar o documento. Tente novamente.";
            }

        }else{
            
            $status = "danger";
            $message = "Não foi possível salvar o documento. Os formatos aceitos são <b>'.pdf', '.png', '.jpeg' e '.jpg'</b>. Tente novamente.";
        }

        $this->session->set_flashdata($status, $message);
        redirect("secretary_doc_requests/{$courseId}");
	}

	private function findAvailableDocs($docPath){

		$docPathExploded = explode('.', $docPath);

		// Path without the extension (.png, .pdf, .jpg or .jpeg)
		$docPath = $docPathExploded[0];
		// Doc registered extension
		$docExtension = $docPathExploded[1];

		// Files extension priority: PDF, PNG, JPG, JPEG
		$extensions = array(
			"pdf" => ".pdf",
			"png" => ".png",
			"jpg" => ".jpg",
			"jpeg" => ".jpeg"
		);
		// Take out the doc extension because it already wasn't found
		unset($extensions[$docExtension]);

		// Trying to find a file with all permitted extensions
		$path = FALSE;
		foreach ($extensions as $extension){
			if(file_exists($docPath.$extension)){
				$path = $docPath.$extension;
				break;
			}
		}

		return $path;
	}

	public function downloadDoc($requestId){

		$requestId = $requestId;
		$request = $this->doc_request_model->getDocRequestById($requestId);
		$docPath = $request['doc_path'];

		$this->load->helper('download');
		if(file_exists($docPath)){
			force_download($docPath, NULL);
		}else{

			$availableDoc = $this->findAvailableDocs($docPath);

			if($availableDoc !== FALSE){
				force_download($availableDoc, NULL);
			}else{
				$status = "danger";
				$message = "Nenhum arquivo encontrado com as extensões permitidas. Comunique a secretaria.";
				$this->session->set_flashdata($status, $message);
	        	redirect("");
			}
		}
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
