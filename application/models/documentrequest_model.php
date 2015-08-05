<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DocumentRequest_model extends CI_Model {

	public function allDocumentTypes(){

		$types = $this->db->get('document_type')->result_array();

		$types = checkArray($types);

		return $types;
	}	
}