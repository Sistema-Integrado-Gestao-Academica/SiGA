<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."secretary/constants/DocumentConstants.php");

class DocumentRequest_model extends CI_Model {

	const DOC_REQUEST_TABLE = "document_request";
	const DOC_TYPE_TABLE = "document_type";

	public function getDocumentType($typeId){

		$type = $this->db->get_where(self::DOC_TYPE_TABLE, array(
			'id_type' => $typeId
		))->row_array();

		$type = checkArray($type);

		return $type;
	}

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

		$foundRequest = $this->getDocumentRequest($documentRequestData, FALSE, FALSE);

		$wasSaved = $foundRequest !== FALSE;

		return $wasSaved;
	}

	public function updateDocFile($requestId, $path){

		$this->db->where("id_request", $requestId);
		$updated = $this->db->update("document_request", array(
			'doc_path' => $path
		));

		return $updated;
	}

	public function getStudentsRequestOfCourse($studentId, $courseId){

		$this->db->order_by('status', "asc");
		$requests = $this->getDocumentRequest(
			array(
				'id_student' => $studentId,
				'id_course' => $courseId,
				'disabled' => DocumentConstants::REQUEST_NON_ARCHIVED
			),
			FALSE,
			FALSE
		);

		return $requests;
	}

	public function getStudentArchivedRequests($studentId, $courseId){

		$this->db->order_by('status', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_student' => $studentId,
			'id_course' => $courseId,
			'disabled' => DocumentConstants::REQUEST_ARCHIVED
		), FALSE, FALSE);

		return $requests;
	}

	public function deleteRequest($requestId){

		$this->db->delete('document_request', array('id_request' => $requestId));

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId), FALSE, FALSE);

		$wasDeleted = $foundRequest === FALSE;

		return $wasDeleted;
	}

	public function getCourseRequests($courseId){

		$this->db->order_by('status', "asc");
		$this->db->order_by('date', "asc");
		$requests = $this->getDocumentRequest(array(
			'id_course' => $courseId,
			'answered' => DocumentConstants::NOT_ANSWERED
		), FALSE, FALSE);

		return $requests;
	}

	public function getAnsweredRequests($courseId){

		$requests = $this->getDocumentRequest(array(
			'id_course' => $courseId,
			'answered' => DocumentConstants::ANSWERED
		), FALSE, FALSE);

		return $requests;
	}

	public function setDocumentReady($requestId, $status = DocumentConstants::REQUEST_READY){

		$this->db->where('id_request', $requestId);
		$this->db->update(
			'document_request',
			array('status' => $status, 'answered' => DocumentConstants::ANSWERED)
		);

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId), FALSE, FALSE);

		if($foundRequest !== FALSE){
			// Since we used the id of the request to the search, will be only one or none result in this array
			foreach($foundRequest as $request){
				$documentIsReady = $request['status'] === $status;
			}
		}else{
			$documentIsReady = FALSE;
		}

		return $documentIsReady;
	}

	public function archiveRequest($requestId){

		$this->db->where('id_request', $requestId);
		$this->db->update('document_request', array('disabled' => DocumentConstants::REQUEST_ARCHIVED));

		$foundRequest = $this->getDocumentRequest(array('id_request' => $requestId), FALSE, FALSE);

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

	public function getDocRequestById($requestId){

		$request = $this->getDocumentRequest("id_request", $requestId);

		return $request;
	}

	private function getDocumentRequest($attr, $value = FALSE, $unique = TRUE){

		if(is_array($attr)){
			$foundRequest = $this->db->get_where(self::DOC_REQUEST_TABLE, $attr);
		}else{
			$foundRequest = $this->db->get_where(self::DOC_REQUEST_TABLE, array($attr => $value));
		}

		if($unique){
			$foundRequest = $foundRequest->row_array();
		}else{
			$foundRequest = $foundRequest->result_array();
		}

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}
}