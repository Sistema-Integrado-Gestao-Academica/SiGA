<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('offer.php');
require_once('semester.php');
require_once('temporaryrequest.php');

require_once(APPPATH."/constants/EnrollmentConstants.php");

class Request extends CI_Controller {

	public function approveAllRequest($requestId, $courseId){

		$this->load->model("request_model");

		$wasRefused = $this->request_model->approveAllRequest($requestId);

		if($wasRefused){
			$status = "success";
			$message = "Toda a solicitação foi aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser aprovada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("request/courseRequests/{$courseId}");		
	}

	public function refuseAllRequest($requestId, $courseId){

		$this->load->model("request_model");

		$wasRefused = $this->request_model->refuseAllRequest($requestId);

		if($wasRefused){
			$status = "success";
			$message = "Toda a solicitação foi recusada com sucesso.";
		}else{
			$status = "danger";
			$message = "Toda a solicitação não pôde ser recusada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("request/courseRequests/{$courseId}");		
	}

	public function approveRequestedDiscipline($requestId, $idOfferDiscipline, $courseId){

		$this->load->model("request_model");

		$wasApproved = $this->request_model->approveRequestedDiscipline($requestId, $idOfferDiscipline);

		if($wasApproved){
			$status = "success";
			$message = "Solicitação de disciplina aprovada com sucesso.";
		}else{
			$status = "danger";
			$message = "Solicitação de disciplina não pôde ser aprovada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("request/courseRequests/{$courseId}");
	}

	public function refuseRequestedDiscipline($requestId, $idOfferDiscipline, $courseId){

		$this->load->model("request_model");

		$wasRefused = $this->request_model->refuseRequestedDiscipline($requestId, $idOfferDiscipline);

		if($wasRefused){
			$status = "success";
			$message = "Solicitação de disciplina recusada com sucesso.";
		}else{
			$status = "danger";
			$message = "Solicitação de disciplina não pôde ser recusada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("request/courseRequests/{$courseId}");	
	}

	public function courseRequests($courseId){

		$this->load->model("request_model");

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$courseRequests = $this->getCourseRequests($courseId, $currentSemester['id_semester']);

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'requests' => $courseRequests,
			'course' => $courseData
		);

		loadTemplateSafelyByGroup("secretario",'request/course_requests', $data);
	}

	public function searchForStudentRequest(){
		
		$this->load->model("request_model");

		$searchType = $this->input->post('searchType');

		$courseId = $this->input->post('courseId');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();
		
		define("SEARCH_BY_STUDENT_ID", "by_id");
		define("SEARCH_BY_STUDENT_NAME", "by_name");

		switch($searchType){
			case SEARCH_BY_STUDENT_ID:
				$studentIds = array();

				$studentId = $this->input->post('student_identifier');
				if(!empty($studentId)){
					$studentIds[] = $studentId;
				}else{
					$studentIds[] = 0;
				}

				$courseRequests = $this->getStudentRequests($courseId, $currentSemester['id_semester'], $studentIds);
				break;

			case SEARCH_BY_STUDENT_NAME:
				$studentName = $this->input->post('student_identifier');

				$user = new Usuario();
				$foundUser = $user->getUserByName($studentName);
				
				if($foundUser !== FALSE){
					$studentId = array();
					foreach($foundUser as $student){
						$studentId[] = $student['id'];
					}
					$courseRequests = $this->getStudentRequests($courseId, $currentSemester['id_semester'], $studentId);
				}else{
					$courseRequests = FALSE;
				}
				break;
			
			default:
				break;
		}		

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$data = array(
			'requests' => $courseRequests,
			'course' => $courseData
		);

		loadTemplateSafelyByGroup("secretario",'request/course_requests', $data);
	}

	private function getStudentRequests($courseId, $semesterId, $studentId){

		$this->load->model("request_model");

		$courseRequests = $this->request_model->getStudentRequests($courseId, $semesterId, $studentId);

		return $courseRequests;
	}

	public function getCourseRequests($courseId, $semesterId){

		$this->load->model("request_model");

		$courseRequests = $this->request_model->getCourseRequests($courseId, $semesterId);

		return $courseRequests;
	}

	public function studentEnrollment($courseId, $userId){

		$this->load->model('request_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$temporaryRequest = new TemporaryRequest();
		$disciplinesToRequest = $temporaryRequest->getUserTempRequest($userId, $courseId, $currentSemester['id_semester']);

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
		}else{
			$data['requestDisciplinesClasses'] = FALSE;
			$data['requestStatus'] = FALSE;
		}

		loadTemplateSafelyByGroup("estudante", 'request/enrollment_request', $data);
	}

	private function getUserRequestDisciplines($userId, $courseId, $semesterId){

		$this->load->model('request_model');
		
		$requestDisciplines = $this->request_model->getUserRequestDisciplines($userId, $courseId, $semesterId);

		return $requestDisciplines;
	}

	private function getRequest($requestData){

		$this->load->model('request_model');
		
		$request = $this->request_model->getRequest($requestData);

		return $request;	
	}

	public function receiveStudentRequest($userRequest){

		if($userRequest !== FALSE){

			// Requisition ids
			$student = $userRequest[0]['id_student'];
			$course = $userRequest[0]['id_course'];
			$semester = $userRequest[0]['id_semester'];

			$requestId = $this->saveNewRequest($student, $course, $semester);

			if($requestId !== FALSE){

				$this->load->model('request_model');

				$offer = new Offer();

				foreach($userRequest as $tempRequest){
					
					$idOfferDiscipline = $tempRequest['discipline_class'];

					$class = $offer->getOfferDisciplineById($idOfferDiscipline);
					$currentVacancies = $class['current_vacancies'];

					// If there is vacancy, enroll student
					if($currentVacancies >= EnrollmentConstants::MIN_VACANCY_QUANTITY_TO_ENROLL){

						/**
							CHECAR RETORNO
						 */
						$this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::PRE_ENROLLED_STATUS);

					}else{
						$this->saveDisciplineRequest($requestId, $idOfferDiscipline, EnrollmentConstants::NO_VACANCY_STATUS);
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
			$offer = new Offer();
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
					$missedDisciplineClass = $offer->getOfferDisciplineById($idOfferDiscipline);
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
		$this->load->model('request_model');
		
		$courseId = $this->request_model->getRequestCourseId($requestId);
		
		return $courseId['id_course'];
	}
	
	public function getRequestDisciplinesClasses($requestId){

		$this->load->model('request_model');
		
		$disciplineClasses = $this->request_model->getRequestDisciplinesClasses($requestId);

		return $disciplineClasses;
	}

	private function getRequestDisciplines($requestId){

		$this->load->model('request_model');
		
		$disciplines = $this->request_model->getRequestDisciplinesById($requestId);

		return $disciplines;
	}

	private function saveDisciplineRequest($requestId, $idOfferDiscipline, $status){

		$this->load->model('request_model');
		
		$wasSaved = $this->request_model->saveDisciplineRequest($requestId, $idOfferDiscipline, $status);

		if($wasSaved){

			$canSubtract = $status !== EnrollmentConstants::NO_VACANCY_STATUS;

			if($canSubtract){
				
				$offer = new Offer();
				$wasSubtracted = $offer->subtractOneVacancy($idOfferDiscipline);

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

	private function saveNewRequest($student, $course, $semester){

		$this->load->model('request_model');
		
		$requisitionId = $this->request_model->saveNewRequest($student, $course, $semester);

		return $requisitionId;
	}
}
