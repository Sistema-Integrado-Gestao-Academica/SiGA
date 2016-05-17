<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/DocumentConstants.php");

class DocumentRequestStudent_model extends CI_Model {

	public function allNonDeclarationTypes(){

		$types = $this->db->get_where('document_type', array('declaration' => DocumentConstants::NON_DECLARATION))->result_array();

		$types = checkArray($types);

		return $types;
	}

	public function allDeclarationTypes(){

		$types = $this->db->get_where('document_type', array('declaration' => DocumentConstants::DECLARATION))->result_array();

		$types = checkArray($types);

		return $types;		
	}

	public function saveDocumentRequest($documentRequestData){

		$solicitationDate = $this->db->query("SELECT NOW()")->row_array();
		
		$documentRequestData['date'] = $solicitationDate['NOW()'];

		$this->db->insert("document_request", $documentRequestData);

		$foundRequest = $this->getDocumentRequest($documentRequestData);

		$wasSaved = $foundRequest !== FALSE;

		return $wasSaved;
	}

	public function getStudentsRequestOfCourse($studentId, $courseId){

		$this->db->order_by('status', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_student' => $studentId,
			'id_course' => $courseId,
			'disabled' => DocumentConstants::REQUEST_NON_ARCHIVED
		));

		return $requests;
	}

	public function getStudentArchivedRequests($studentId, $courseId){

		$this->db->order_by('status', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_student' => $studentId,
			'id_course' => $courseId,
			'disabled' => DocumentConstants::REQUEST_ARCHIVED
		));

		return $requests;
	}

	public function deleteRequest($requestId){

		$this->db->delete('document_request', array('id_request' => $requestId));

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId));

		$wasDeleted = $foundRequest === FALSE;

		return $wasDeleted;
	}

	public function getCourseRequests($courseId){

		$this->db->order_by('status', "asc");
		$this->db->order_by('date', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_course' => $courseId,
			'answered' => DocumentConstants::NOT_ANSWERED
		));

		return $requests;
	}

	public function getAnsweredRequests($courseId){

		$requests = $this->getDocumentRequest(array(
			'id_course' => $courseId,
			'answered' => DocumentConstants::ANSWERED
		));

		return $requests;
	}

	public function setDocumentReady($requestId){

		$this->db->where('id_request', $requestId);
		$this->db->update(
			'document_request',
			array('status' => DocumentConstants::REQUEST_READY, 'answered' => DocumentConstants::ANSWERED)
		);

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId));

		if($foundRequest !== FALSE){
			// Since we used the id of the request to the search, will be only one or none result in this array
			foreach($foundRequest as $request){
				$documentIsReady = $request['status'] === DocumentConstants::REQUEST_READY;
			}
		}else{
			$documentIsReady = FALSE;
		}

		return $documentIsReady;
	}

	public function archiveRequest($requestId){

		$this->db->where('id_request', $requestId);
		$this->db->update('document_request', array('disabled' => DocumentConstants::REQUEST_ARCHIVED));

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId));

		if($foundRequest !== FALSE){
			// Since we used the id of the request to the search, will be only one or none result in this array
			foreach($foundRequest as $request){
				$documentIsArchived = $request['disabled'] == DocumentConstants::REQUEST_ARCHIVED;
			}
		}else{
			$documentIsArchived = FALSE;
		}

		return $documentIsArchived;
	}

	private function getDocumentRequest($requestData){

		$documentRequest = $this->db->get_where('document_request', $requestData)->result_array();

		$documentRequest = checkArray($documentRequest);

		return $documentRequest;
	}
};