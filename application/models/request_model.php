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

		$registeredRequest = $this->getRequest($requestData);

		if($registeredRequest !== FALSE){
			$requestId = $registeredRequest['id_request'];
		}else{

			$this->db->insert('student_request', $requestData);

			$foundRequest = $this->getRequest($requestData);

			if($foundRequest !== FALSE){
				$requestId = $foundRequest['id_request'];
			}else{
				$requestId = FALSE;
			}
		}

		return $requestId;
	}

	public function saveDisciplineRequest($requestId, $idOfferDiscipline, $status){

		$requestDiscipline = array(
			'id_request' => $requestId,
			'discipline_class' => $idOfferDiscipline,
			'status' => $status
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

	public function getStudentRequests($courseId, $semesterId, $studentIds){

		$this->db->select("student_request.*, request_discipline.*");
		$this->db->from("student_request");
		$this->db->join("request_discipline", "student_request.id_request = request_discipline.id_request");
		$this->db->where("student_request.id_course", $courseId);
		$this->db->where("student_request.id_semester", $semesterId);
		
		$ids = $studentIds;

		$whereClause = "(";
		foreach($studentIds as $key => $studentId){
			
			if(hasNext($ids)){
				unset($ids[$key]);
				$whereClause = $whereClause."student_request.id_student = {$studentId} OR ";
			}else{
				$whereClause = $whereClause." student_request.id_student = {$studentId}";
			}
		}
		$whereClause = $whereClause.")";

		$this->db->where($whereClause);
		$this->db->order_by("request_status", "asc");

		$foundRequest = $this->db->get()->result_array();

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}

	public function getRequestDisciplinesById($requestId){

		$disciplines = $this->getRequestDisciplines(array('id_request' => $requestId));

		return $disciplines;
	}

	private function getRequestDisciplines($requestDisciplineData){

		$foundRequestDiscipline = $this->db->get_where('request_discipline', $requestDisciplineData)->result_array();

		$foundRequestDiscipline = checkArray($foundRequestDiscipline);

		return $foundRequestDiscipline;
	}

	public function getUserRequestDisciplines($userId, $courseId, $semesterId){

		$requestData = array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId
		);

		$request = $this->request_model->getRequest($requestData);

		if($request !== FALSE){

			$requestStatus = $request['request_status'];

			$classes = $this->getRequestDisciplinesClasses($request['id_request']);

			$requestDisciplinesClasses = array(
				'requestStatus' => $requestStatus,
				'requestDisciplinesClasses' => $classes
			);
			
		}else{
			$requestDisciplinesClasses = FALSE;
		}

		return $requestDisciplinesClasses;
	}

	public function getRequestDisciplinesClasses($requestId){

		$this->db->select('offer_discipline.*, request_discipline.status');
		$this->db->from('request_discipline');
		$this->db->join('offer_discipline', "request_discipline.discipline_class = offer_discipline.id_offer_discipline");
		$this->db->where('request_discipline.id_request', $requestId);
		$foundClasses = $this->db->get()->result_array();

		$foundClasses = checkArray($foundClasses);

		return $foundClasses;
	}

	public function getRequest($requestData){

		$foundRequest = $this->db->get_where('student_request', $requestData)->row_array();

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}

	public function getCourseRequests($courseId, $semesterId){

		$this->db->select("student_request.*, request_discipline.*");
		$this->db->from("student_request");
		$this->db->join("request_discipline", "student_request.id_request = request_discipline.id_request");
		$this->db->where("student_request.id_course", $courseId);
		$this->db->where("student_request.id_semester", $semesterId);
		$this->db->order_by("request_status", "asc");

		$foundRequest = $this->db->get()->result_array();

		$foundRequest = checkArray($foundRequest);

		return $foundRequest;
	}
}
