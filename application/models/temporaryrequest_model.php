<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TemporaryRequest_model extends CI_Model {

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$conditions = array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId
		);

		$foundRequest = $this->db->get_where('temporary_student_request', $conditions)->row_array();

		if(sizeof($foundRequest) > 0){
			// Nothing to do
		}else{
			$foundRequest = FALSE;
		}

		return $foundRequest;
	}
}
