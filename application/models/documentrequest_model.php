<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

	private function getDocumentRequest($requestData){

		$documentRequest = $this->db->get_where('document_request', $requestData)->result_array();

		$documentRequest = checkArray($documentRequest);

		return $documentRequest;
	}
}