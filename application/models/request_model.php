<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_model extends CI_Model {

	public function saveNewRequest($student, $course, $semester){

		define("INCOMPLETE", "incomplete");

		$requestData = array(
			'id_student' => $student,
			'id_course' => $course,
			'id_semester' => $semester,
			'request_status' => INCOMPLETE
		);

		$this->db->insert('student_request', $requestData);

		$foundRequest = $this->getRequest($requestData);

		if($foundRequest !== FALSE){
			$requestId = $foundRequest['id_request'];
		}else{
			$requestId = FALSE;
		}

		return $requestId;
	}

	public function saveDisciplineRequest($requestId, $idOfferDiscipline){

		$requestDiscipline = array(
			'id_request' => $requestId,
			'discipline_class' => $idOfferDiscipline
		);

		$this->db->insert('request_discipline', $requestDiscipline);

		$foundRequest = $this->getRequestDisciplines($requestDiscipline);

		if($foundRequest !== FALSE){
			$wasSaved = TRUE;
		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	private function getRequestDisciplines($requestDisciplineData){

		$foundRequestDiscipline = $this->db->get_where('request_discipline', $requestDisciplineData)->row_array();

		if(sizeof($foundRequestDiscipline) > 0){
			// Nothing to do
		}else{
			$foundRequestDiscipline = FALSE;
		}

		return $foundRequestDiscipline;
	}

	public function getRequest($requestData){

		$foundRequest = $this->db->get_where('student_request', $requestData)->row_array();

		if(sizeof($foundRequest) > 0){
			// Nothing to do
		}else{
			$foundRequest = FALSE;
		}

		return $foundRequest;
	}
}
