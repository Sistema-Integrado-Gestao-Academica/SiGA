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

	public function approveRequestedDisciplineSecretary($requestId, $idOfferDiscipline, $requestDate){

		$this->approveRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_SECRETARY, $requestDate);
	}

	public function refuseRequestedDisciplineSecretary($requestId, $idOfferDiscipline, $requestDate){

		$this->refuseRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_SECRETARY, $requestDate);
	}

	public function approveRequestedDisciplineMastermind($requestId, $idOfferDiscipline, $requestDate){

		$this->approveRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_MASTERMIND, $requestDate);
	}

	public function refuseRequestedDisciplineMastermind($requestId, $idOfferDiscipline, $requestDate){

		$this->refuseRequestedDiscipline($requestId, $idOfferDiscipline, EnrollmentConstants::REQUESTING_AREA_MASTERMIND, $requestDate);
	}

	private function approveRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea, $requestDate=NULL){

		$this->load->model("secretary/offer_model");

		$offerDiscipline = $this->offer_model->getOfferDisciplineById($idOfferDiscipline);

		if($offerDiscipline['current_vacancies'] > EnrollmentConstants::NO_VACANCY){
			$this->request_model->approveRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea, $requestDate);
		}
	}

	private function refuseRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea, $requestDate=NULL){

		$wasRefused = $this->request_model->refuseRequestedDiscipline($requestId, $idOfferDiscipline, $requestingArea, $requestDate);

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

	public function studentResendRequest($requestId){

		$request = $this->getRequestById($requestId);
		$course = $request['id_course'];
		$semester = $request['id_semester'];

		// Get the request offer data
		$this->load->model("secretary/offer_model");
		$needMastermindApproval = $this->offer_model->needMastermindApproval($semester, $course);

		if($needMastermindApproval){
			$role = EnrollmentConstants::REQUEST_TO_MASTERMIND;
		}else{
			$role = EnrollmentConstants::REQUEST_TO_SECRETARY;
		}

		$this->request_model->updateMastermindApproval($requestId, EnrollmentConstants::REQUEST_NOT_APPROVED_BY_MASTERMIND);

		$this->request_model->updateCurrentRoleFromStudent($requestId, $role);

		$status = "success";
		$message = "Solicitação reenviada com sucesso.";

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("update_enroll_request/{$requestId}");
	}

	public function makeAvailableToStudent($requestId, $courseId){

		$this->request_model->updateCurrentRole($requestId, EnrollmentConstants::REQUEST_TO_STUDENT);

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

		//----/ Used in the view
		$this->load->model('secretary/offer_model');
		$this->load->model('program/discipline_model');
		//----/

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

			$data['requestStatus'] = $requestForSemester['requestStatus'];

			$request = $this->getRequest(array(
				'id_student' => $userId,
				'id_course' => $courseId,
				'id_semester' => $currentSemester['id_semester']
			));

			$requestId = $request['id_request'];
			$data['requestId'] = $requestId;
			$data['request'] = $this->getRequestById($requestId);

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

	public function updateRequest($requestId){

		$this->load->model("program/semester_model");

		$request = $this->request_model->getRequest(array('id_request' =>$requestId));

		$user = getSession()->getUserData()->getId();

		if($request['id_student'] == $user){

			$disciplines = $this->request_model->getRequestDisciplinesById($requestId);
			$currentSemester = $this->semester_model->getCurrentSemester();

			$data = array(
				"courseId" => $request['id_course'],
				"userId" => $request['id_student'],
				"request" => $request,
				"disciplines" => $disciplines,
				"semester" => $currentSemester
			);

			loadTemplateSafelyByGroup(GroupConstants::STUDENT_GROUP, 'secretary/request/update_enrollment_request', $data);
		}else{
			show_404();
		}
	}

	private function disciplineInRequest($disciplines, $idOfferDiscipline){
		$inRequest = FALSE;
		foreach($disciplines as $discipline){
			if($discipline['discipline_class'] == $idOfferDiscipline
				&& $discipline['status'] !== EnrollmentConstants::NO_VACANCY_STATUS){
				$inRequest = TRUE;
				break;
			}
		}
		return $inRequest;
	}

	public function addDisciplineToRequest($requestId, $idOfferDiscipline){

		$this->load->module("secretary/schedule");

		$requestDisciplines = $this->request_model->getRequestDisciplinesById($requestId);

		$alreadyRequested = $this->disciplineInRequest($requestDisciplines, $idOfferDiscipline);

		if(!$alreadyRequested){

/*****************  GET THIS BETTER */
			$this->schedule->getDisciplineHours($idOfferDiscipline);
			$requestedDisciplineSchedule = $this->schedule->getDisciplineSchedule();

			$this->schedule->emptySchedule();

			// Get disciplines hours from already inserted to resquest disciplines
			$insertedDisciplines = array();
			foreach($requestDisciplines as $discipline){

				// Take the disciplines that was refused out, so their schedule be free to other disciplines
				if($discipline['status'] !== EnrollmentConstants::REFUSED_STATUS
					&& $discipline['status'] !== EnrollmentConstants::NO_VACANCY_STATUS){
					$this->schedule->getDisciplineHours($discipline['discipline_class']);
					$disciplineSchedule = $this->schedule->getDisciplineSchedule();
					$insertedDisciplines[] = $disciplineSchedule;
				}
			}

			$conflicts = $this->schedule->checkHourConflits($requestedDisciplineSchedule, $insertedDisciplines);

/*****************  GET THIS BETTER */

			if($conflicts === FALSE){
				$this->load->model("secretary/offer_model");
				$offerDiscipline = $this->offer_model->getOfferDisciplineById($idOfferDiscipline);
				$offer = $this->offer_model->getOffer($offerDiscipline['id_offer']);

				$mastermindApproval = $this->checkMastermindNeed($offer['semester'], $offer['course'], $requestId);

				$saved = $this->checkVacanciesAndSave($requestId, $idOfferDiscipline, $mastermindApproval, TRUE);

				$status = $saved ? "success" : "danger";
				$message = $saved ? "Disciplina adicionada com sucesso!" : "Não foi possível salvar a disciplina solicitada. Tente novamente.";
			}else{
				$status = "danger";
				$message = "Não foi possível adicionar a disciplina pedida porque houve conflito de horários com disciplinas já adicionadas.<br>
				<i>Conflito no horário <b>".$conflicts->getDayHourPair()."</b>.</i>";
			}
		}else{
			$status = "danger";
			$message = "Disciplina já solicitada.";
		}

		getSession()->showFlashMessage($status, $message);
		redirect("update_enroll_request/{$requestId}");
	}

	public function removeDisciplineFromRequest($requestId, $idOfferDiscipline){

		$this->request_model->removeDisciplineRequest($requestId, $idOfferDiscipline);

		$status = "success";
		$message = "Disciplina removida com sucesso!";

		getSession()->showFlashMessage($status, $message);
		redirect("update_enroll_request/{$requestId}");
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
			$mastermindApproval = $this->checkMastermindNeed($semester, $course);

			$requestId = $this->saveNewRequest($student, $course, $semester, $mastermindApproval);

			if($requestId !== FALSE){

				foreach($userRequest as $tempRequest){

					$idOfferDiscipline = $tempRequest['discipline_class'];

					$this->checkVacanciesAndSave($requestId, $idOfferDiscipline, $mastermindApproval);
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

	private function checkMastermindNeed($semester, $course, $requestId=FALSE){

		$this->load->model("secretary/offer_model");
		$requestedOffer = $this->offer_model->getOfferBySemesterAndCourse($semester, $course);
		$needsMastermindApproval = $requestedOffer['needs_mastermind_approval'] == EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL;

		if($needsMastermindApproval){

			$mastermindApproval = EnrollmentConstants::REQUEST_NOT_APPROVED_BY_MASTERMIND;

			if($requestId !== FALSE){
				$request = $this->getRequestById($requestId);
				$mastermindFinalized = $request['mastermind_approval'];

				//If the mastermind already finalized his moves, only the secretary can play with the request
				if($mastermindFinalized){
					$mastermindApproval = EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;
				}
			}

		}else{
			$mastermindApproval = EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;
		}

		return $mastermindApproval;
	}

	private function checkVacanciesAndSave($requestId, $idOfferDiscipline, $mastermindApproval=0, $isUpdate=FALSE){

		$this->load->model("secretary/offer_model");

		$class = $this->offer_model->getOfferDisciplineById($idOfferDiscipline);
		$currentVacancies = $class['current_vacancies'];

		// If there is vacancy, enroll student
		if($currentVacancies >= EnrollmentConstants::MIN_VACANCY_QUANTITY_TO_ENROLL){

			$saved = $this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::PRE_ENROLLED_STATUS, $mastermindApproval, $isUpdate);

		}else{
			$saved = $this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::NO_VACANCY_STATUS, $mastermindApproval, $isUpdate);
		}

		return $saved;
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

	private function saveDisciplineRequest($requestId, $idOfferDiscipline, $status, $mastermindApproval=0, $isUpdate=FALSE){

		$this->db->trans_start();

		$wasSaved = $this->request_model->saveDisciplineRequest($requestId, $idOfferDiscipline, $status, $mastermindApproval, $isUpdate);

		$subtracted = TRUE;
		if($wasSaved){

			$canSubtract = $status !== EnrollmentConstants::NO_VACANCY_STATUS;

			if($canSubtract){
				$this->load->model("secretary/offer_model");
				$subtracted = $this->offer_model->subtractOneVacancy($idOfferDiscipline);
			}
		}
		$this->db->trans_complete();

		$ok = $this->db->trans_status() !== FALSE && $subtracted;

		return $ok;
	}

	private function saveNewRequest($student, $course, $semester, $mastermindApproval = 0){
		$requisitionId = $this->request_model->saveNewRequest($student, $course, $semester, $mastermindApproval);

		return $requisitionId;
	}
}
