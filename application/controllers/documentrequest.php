<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("usuario.php");
require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/constants/DocumentConstants.php");

class DocumentRequest extends CI_Controller {

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

				break;

			default:
				break;
		}
	}

}
