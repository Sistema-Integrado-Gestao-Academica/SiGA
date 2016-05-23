<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."secretary/constants/EnrollmentConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");

class Request extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('secretary/request_model');
	}

	public function approveAllRequest($requestId, $courseId){

		$wasApproved = $this->request_model->approveAllRequest($requestId);

		if($wasApproved){
			$status = "success";
			$message = "Toda a solicitação foi aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser aprovada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/request/courseRequests/{$courseId}");
	}

	public function refuseAllRequest($requestId, $courseId){
		
		$wasRefused = $this->request_model->refuseAllRequest($requestId);

		if($wasRefused){
			$status = "success";
			$message = "Toda a solicitação foi recusada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser recusada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/request/courseRequests/{$courseId}");
	}


	public function approveAllStudentRequestsByMastermind($requestId, $studentId){

		
		$wasApproved = $this->request_model->mastermindApproveAllCurrentStudentRequest($requestId);

		if($wasApproved){
			$status = "success";
			$message = "Toda a solicitação foi aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser aprovada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('mastermind');
	}

	public function refuseAllStudentRequestsByMastermind($requestId, $studentId){

		
		$wasRefused = $this->request_model->mastermindRefuseAllCurrentStudentRequest($requestId);

		if($wasRefused){
			$status = "success";
			$message = "Toda a solicitação foi reprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser reprovada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('mastermind');
	}

	public function approveRequestedDisciplineSecretary($requestId, $idOfferDiscipline, $courseId){

		$this->approveRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_SECRETARY);

		redirect("secretary/request/courseRequests/{$courseId}");
	}

	public function refuseRequestedDisciplineSecretary($requestId, $idOfferDiscipline, $courseId){

		$this->refuseRequestedDiscipline($requestId, $idOfferDiscipline, $courseId, EnrollmentConstants::REQUESTING_AREA_SECRETARY);

		redirect("secretary/request/courseRequests/{$courseId}");
	}

	public function approveRequestedDisciplineMastermind($requestId, $idOfferDiscipline, $courseId){

		$this->approveRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_MASTERMIND);

		redirect("mastermind");
	}

	public function refuseRequestedDisciplineMastermind($requestId, $idOfferDiscipline, $courseId){

		$this->refuseRequestedDiscipline($requestId, $idOfferDiscipline, $courseId, EnrollmentConstants::REQUESTING_AREA_MASTERMIND);

		redirect("mastermind");
	}

	private function approveRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea){

		
		$wasApproved = $this->request_model->approveRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea);

		if($wasApproved){
			$status = "success";
			$message = "Solicitação de disciplina aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Solicitação de disciplina não pôde ser aprovada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
	}

	private function refuseRequestedDiscipline($requestId, $idOfferDiscipline, $courseId, $requestingArea){

		
		$wasRefused = $this->request_model->refuseRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea);

		if($wasRefused){
			$status = "success";
			$message = "Solicitação de disciplina recusada com sucesso.";
		}else{
			$status = "danger";
			$message = "Solicitação de disciplina não pôde ser recusada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
	}

	public function finalizeRequestToMastermind($requestId){

		$wasFinalized = $this->request_model->finalizeRequestToMastermind($requestId);

		return $wasFinalized;
	}

	public function finalizeRequestSecretary($requestId, $courseId){


		$wasFinalized = $this->request_model->finalizeRequestSecretary($requestId);

		if($wasFinalized){
			$status = "success";
			$message = "Solicitação finalizada com sucesso.";
		}else{
			$status = "danger";
			$message = "A solicitação não pôde ser finalizada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/request/courseRequests/{$courseId}");
	}

	public function saveMastermindMessage($mastermindId, $requestId, $message){

		
		$messageSaved = $this->request_model->saveMastermindMessage($mastermindId, $requestId, $message);

		return $messageSaved;
	}

	public function courseRequests($courseId){

		
		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		$courseRequests = $this->getCourseRequests($courseId, $currentSemester['id_semester']);

		$this->load->module("program/course");
		$courseData = $this->course->getCourseById($courseId);

		$users = $this->getUsersRequest($courseRequests);

		$data = array(
			'requests' => $courseRequests,
			'course' => $courseData,
			'users' => $users
		);

		loadTemplateSafelyByGroup(GroupConstants::SECRETARY_GROUP,'secretary/request/course_requests', $data);
	}

	private function getUsersRequest($requests){

		$users = array();
		if($requests !== FALSE){

			foreach ($requests as $request) {
				
				$requestId = $request['id_request'];
				$userId = $request['id_student'];

				$this->load->model("student/student_model");
				$user = $this->student_model->getNameAndEnrollment($userId);
		
				$users[$requestId]['name'] = $user[0]['name'];
				$users[$requestId]['enrollment'] = $user[0]['enrollment'];

			}

		}
		else{
			$users = FALSE;
		}

		return $users;
	}

	public function searchForStudentRequest(){

		
		$searchType = $this->input->post('searchType');

		$courseId = $this->input->post('courseId');

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		define("SEARCH_BY_STUDENT_ENROLLMENT", "by_enrollment");
		define("SEARCH_BY_STUDENT_NAME", "by_name");
		
		$student = $this->input->post('student_identifier');
		$courseRequests = array();
		switch($searchType){
			case SEARCH_BY_STUDENT_ENROLLMENT:

				$this->load->model("student/student_model");
				$foundUser = $this->student_model->getUserByEnrollment($student);

				$courseRequests = $this->getStudentsIdsForSearchRequests($courseId, $currentSemester['id_semester'], $foundUser);

				break;

			case SEARCH_BY_STUDENT_NAME:

				$this->load->model("auth/usuarios_model");
				$foundUser = $this->usuarios_model->getUserByName($student);

				$courseRequests = $this->getStudentsIdsForSearchRequests($courseId, $currentSemester['id_semester'], $foundUser);

				break;

			default:
				break;
		}

		$this->load->model("program/course_model");
		$courseData = $this->course_model->getCourseById($courseId);

		$users = $this->getUsersRequest($courseRequests);

		$data = array(
			'requests' => $courseRequests,
			'course' => $courseData,
			'users' => $users
		);

		loadTemplateSafelyByGroup(GroupConstants::SECRETARY_GROUP,'secretary/request/course_requests', $data);
	}

	private function getStudentsIdsForSearchRequests($courseId, $semesterId, $users){

		$courseRequests = array();
		if($users !== FALSE){
			$userIds = array();
			foreach($users as $key => $user){
				$studentsIds[$key] = $user['id'];
			}
						$courseRequests = $this->request_model->getStudentRequests($courseId, $semesterId, $studentsIds);
		}
		else{
			$courseRequests = FALSE;
		}

		return $courseRequests;
	}

	public function getCourseRequests($courseId, $semesterId){

		
		$courseRequests = $this->request_model->getCourseRequests($courseId, $semesterId);

		return $courseRequests;
	}

	public function studentEnrollment($courseId, $userId){

		$this->load->model('secretary/request_model');

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		$this->load->model("student/temporaryrequest_model");
		$disciplinesToRequest = $this->temporaryrequest_model->getUserTempRequest($userId, $courseId, $currentSemester['id_semester']);

		$thereIsDisciplinesToRequest = $disciplinesToRequest !== FALSE;

		$data = array(
			'semester' => $currentSemester,
			'courseId' => $courseId,
			'userId' => $userId,
			'disciplinesToRequest' => $disciplinesToRequest,
			'thereIsDisciplinesToRequest' => $thereIsDisciplinesToRequest,
		);

		$requestForSemester = $this->getUserRequestDisciplines($userId, $courseId, $currentSemester['id_semester']);
		if($requestForSemester !== FALSE){

			$data['requestDisciplinesClasses'] = $requestForSemester['requestDisciplinesClasses'];

			switch($requestForSemester['requestStatus']){
				case EnrollmentConstants::REQUEST_INCOMPLETE_STATUS:
					$requestStatus = "Incompleta (Aguardar aprovação do coordenador)";
					break;

				case EnrollmentConstants::REQUEST_ALL_APPROVED_STATUS:
					$requestStatus = "Aprovada";
					break;

				case EnrollmentConstants::REQUEST_ALL_REFUSED_STATUS:
					$requestStatus = "Recusada";
					break;

				case EnrollmentConstants::REQUEST_PARTIALLY_APPROVED_STATUS:
					$requestStatus = "Parcialmente aprovada";
					break;

				default:
					$requestStatus = "-";
					break;
			}
			$data['requestStatus'] = $requestStatus;

			$request = $this->getRequest(array(
				'id_student' => $userId,
				'id_course' => $courseId,
				'id_semester' => $currentSemester['id_semester']
			));

			$requestId = $request['id_request'];

			$this->load->module("program/mastermind");
			$mastermindId = $this->mastermind->getMastermindByStudent($userId);

			$mastermindMessage = $this->mastermind->getMastermindMessage($mastermindId, $requestId);

			$data['mastermindMessage'] = $mastermindMessage;

		}else{
			$data['requestDisciplinesClasses'] = FALSE;
			$data['requestStatus'] = FALSE;
		}

		loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'secretary/request/enrollment_request', $data);
	}

	private function getUserRequestDisciplines($userId, $courseId, $semesterId){

		$requestDisciplines = $this->request_model->getUserRequestDisciplines($userId, $courseId, $semesterId);

		return $requestDisciplines;
	}

	private function getMastermindMessage($userId, $courseId, $semesterId){

		$mastermindMessage = $this->request_model->getMastermindMessage($userId, $courseId, $semesterId);

		return $mastermindMessage;
	}

	public function getRequestById($requestId){

		$foundRequest = $this->getRequest(array('id_request' => $requestId));

		return $foundRequest;
	}

	private function getRequest($requestData){


		$request = $this->request_model->getRequest($requestData);

		return $request;
	}

	public function receiveStudentRequest($userRequest){

		if($userRequest !== FALSE){

			// Requisition ids
			$student = $userRequest[0]['id_student'];
			$course = $userRequest[0]['id_course'];
			$semester = $userRequest[0]['id_semester'];

	
			// Check in the offer if the request needs to be approved by mastermind first
			$offer = new Offer();
			$requestedOffer = $offer->getOfferBySemesterAndCourse($semester, $course);
			$needsMastermindApproval = $requestedOffer['needs_mastermind_approval'] == EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL;

			if($needsMastermindApproval){

				$mastermindApproval = EnrollmentConstants::REQUEST_NOT_APPROVED_BY_MASTERMIND;
			}else{
				$mastermindApproval = EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;
			}

			$requestId = $this->saveNewRequest($student, $course, $semester, $mastermindApproval);

			if($requestId !== FALSE){

				foreach($userRequest as $tempRequest){

					$idOfferDiscipline = $tempRequest['discipline_class'];

					$class = $offer->getOfferDisciplineById($idOfferDiscipline);
					$currentVacancies = $class['current_vacancies'];

					// If there is vacancy, enroll student
					if($currentVacancies >= EnrollmentConstants::MIN_VACANCY_QUANTITY_TO_ENROLL){

						/**
							CHECAR RETORNO
						 */
						$this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::PRE_ENROLLED_STATUS, $mastermindApproval);

					}else{
						$this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::NO_VACANCY_STATUS, $mastermindApproval);
					}
				}

				$wasReceived = $this->checkIfRequestWasSaved($userRequest, $requestId);

			}else{
				$wasReceived = FALSE;
			}

		}else{
			$wasReceived = FALSE;
		}

		return $wasReceived;
	}

	private function checkIfRequestWasSaved($userRequest, $requestId){

		$noSavedDisciplines = array();
		$i = 0;
		$savedRequestDisciplines = $this->getRequestDisciplines($requestId);
		if($savedRequestDisciplines !== FALSE){
			$this->load->model("secretary/offer_model");
			foreach($userRequest as $tempRequest){

				$idOfferDiscipline = $tempRequest['discipline_class'];
				$wasSaved = FALSE;
				foreach($savedRequestDisciplines as $requestDiscipline){

					if($requestDiscipline['discipline_class'] === $idOfferDiscipline){
						$wasSaved = TRUE;
						break;
					}
				}

				if($wasSaved){
					// Nothing to do because was saved
				}else{
					$missedDisciplineClass = $this->offer_model->getOfferDisciplineById($idOfferDiscipline);
					$noSavedDisciplines[$i] = $missedDisciplineClass;
					$i++;
				}
			}

			$wasReceived = $noSavedDisciplines;
		}else{
			$wasReceived = FALSE;
		}

		return $wasReceived;
	}

	public function getCourseIdByIdRequest($requestId){

		$courseId = $this->request_model->getRequestCourseId($requestId);

		return $courseId['id_course'];
	}

	public function getRequestDisciplinesClasses($requestId){


		$disciplineClasses = $this->request_model->getRequestDisciplinesClasses($requestId);

		return $disciplineClasses;
	}

	private function getRequestDisciplines($requestId){


		$disciplines = $this->request_model->getRequestDisciplinesById($requestId);

		return $disciplines;
	}

	private function saveDisciplineRequest($requestId, $idOfferDiscipline, $status, $mastermindApproval = 0){


		$wasSaved = $this->request_model->saveDisciplineRequest($requestId, $idOfferDiscipline, $status, $mastermindApproval);

		if($wasSaved){

			$canSubtract = $status !== EnrollmentConstants::NO_VACANCY_STATUS;

			if($canSubtract){

				$this->load->model("secretary/offer_model");
				$wasSubtracted = $this->offer_model->subtractOneVacancy($idOfferDiscipline);

				if($wasSubtracted){
					$disciplineWasAdded = TRUE;

				}else{

					/**

					  In this case, the discipline might be saved but the vacancy might be not subtracted

					 */
					$disciplineWasAdded = FALSE;
				}
			}else{
				// If there is no vacancy, don't need to subtract the vacancy
				$disciplineWasAdded = TRUE;
			}
		}else{
			$disciplineWasAdded = FALSE;
		}

		return $disciplineWasAdded;
	}

	private function saveNewRequest($student, $course, $semester, $mastermindApproval = 0){


		$requisitionId = $this->request_model->saveNewRequest($student, $course, $semester, $mastermindApproval);

		return $requisitionId;
	}
}
