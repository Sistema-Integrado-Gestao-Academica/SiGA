<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemporaryRequest_model extends CI_Model {

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$conditions = array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId
		);

		$foundRequest = $this->db->get_where('temporary_student_request', $conditions)->result_array();

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}

	public function cleanUserTempRequest($userId, $courseId, $semesterId){

		$this->db->delete('temporary_student_request', array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId
		));

		$foundRequest = $this->getUserTempRequest($userId, $courseId, $semesterId);

		if($foundRequest !== FALSE){
			$wasCleaned = FALSE;
		}else{
			$wasCleaned = TRUE;
		}
		
		return $wasCleaned;
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

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}

}
