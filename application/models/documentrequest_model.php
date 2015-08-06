<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/DocumentConstants.php");

class DocumentRequest_model extends CI_Model {

	public function allDocumentTypes(){

		$types = $this->db->get('document_type')->result_array();

		$types = checkArray($types);

		return $types;
	}

	public function saveDocumentRequest($documentRequestData){

		$this->db->insert("document_request", $documentRequestData);

		$foundRequest = $this->getDocumentRequest($documentRequestData);

		$wasSaved = $foundRequest !== FALSE;

		return $wasSaved;
	}

	public function getStudentsRequestOfCourse($studentId, $courseId){

		$this->db->order_by('status', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_student' => $studentId,
			'id_course' => $courseId
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
		$requests = $this->getDocumentRequest(array(
			'id_course' => $courseId
		));

		return $requests;
	}

	public function setDocumentReady($requestId){

		$this->db->where('id_request', $requestId);
		$this->db->update('document_request', array('status' => DocumentConstants::REQUEST_READY));

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

	private function getDocumentRequest($requestData){

		$documentRequest = $this->db->get_where('document_request', $requestData)->result_array();

		$documentRequest = checkArray($documentRequest);

		return $documentRequest;
	}
};