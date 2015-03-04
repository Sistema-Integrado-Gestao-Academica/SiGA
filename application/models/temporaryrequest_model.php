<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemporaryRequest_model extends CI_Model {

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$conditions = array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId
		);

		$foundRequest = $this->db->get_where('temporary_student_request', $conditions)->result_array();

		if(sizeof($foundRequest) > 0){
			// Nothing to do
		}else{
			$foundRequest = FALSE;
		}

		return $foundRequest;
	}

	public function saveTempRequest($tempRequestData){

		$this->db->insert('temporary_student_request', $tempRequestData);

		$foundRequest = $this->getTempRequest($tempRequestData);

		if($foundRequest !== FALSE){
			$requestWasSaved = TRUE;
		}else{
			$requestWasSaved = FALSE;
		}

		return $requestWasSaved;
	}

	public function removeTempRequest($requestToRemove){

		$foundRequest = $this->getTempRequest($requestToRemove);
		if($foundRequest !== FALSE){
			
			$this->db->delete('temporary_student_request', $requestToRemove);

			$foundRequest = $this->getTempRequest($requestToRemove);

			if($foundRequest !== FALSE){
				$requestWasRemoved = FALSE;
			}else{
				$requestWasRemoved = TRUE;
			}
		}else{
			$requestWasRemoved = FALSE;
		}

		return $requestWasRemoved;
	}

	public function getTempRequest($tempRequestData){

		$foundRequest = $this->db->get_where('temporary_student_request', $tempRequestData)->row_array();

		if(sizeof($foundRequest) > 0){
			// Nothing to do
		}else{
			$foundRequest = FALSE;
		}

		return $foundRequest;
	}

}
