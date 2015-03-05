<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('offer.php');
require_once('semester.php');
require_once('temporaryrequest.php');

class Request extends CI_Controller {

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
				case 'incomplete':
					$requestStatus = "Incompleta (Aguardar aprovação do coordenador)";
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

	public function receiveStudentRequest($userResquest){

		if($userResquest !== FALSE){

			// Requisition ids
			$student = $userResquest[0]['id_student'];
			$course = $userResquest[0]['id_course'];
			$semester = $userResquest[0]['id_semester'];

			$requestId = $this->saveNewRequest($student, $course, $semester);

			if($requestId !== FALSE){

				define("MIN_VACANCY_QUANTITY_TO_ENROLL", 1);

				$this->load->model('request_model');

				$offer = new Offer();

				$noVacancyClasses = array();
				$i = 0;
				foreach($userResquest as $tempRequest){
					
					$idOfferDiscipline = $tempRequest['discipline_class'];

					$class = $offer->getOfferDisciplineById($idOfferDiscipline);
					$currentVacancies = $class['current_vacancies'];

					// If there is vacancy, enroll student
					if($currentVacancies >= MIN_VACANCY_QUANTITY_TO_ENROLL){

						/**
							CHECAR RETORNO
						 */
						$this->saveDisciplineRequest($requestId, $idOfferDiscipline);

					}else{
						$noVacancyClasses[$i] = $class;
						$i++;
					}
				}

				/**
				  VERIFICAR NO BANCO SE TODAS AS DISCIPLINAS(TURMAS) PEDIDAS (COM EXCEÇÃO DAS SEM VAGAS) FORAM SALVAS
				 */
				$wasReceived = $noVacancyClasses;

			}else{
				$wasReceived = FALSE;
			}

		}else{
			$wasReceived = FALSE;
		}

		return $wasReceived;
	}

	private function saveDisciplineRequest($requestId, $idOfferDiscipline){

		$this->load->model('request_model');
		
		$wasSaved = $this->request_model->saveDisciplineRequest($requestId, $idOfferDiscipline);

		if($wasSaved){
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
			$disciplineWasAdded = TRUE;
		}

		return $disciplineWasAdded;
	}

	private function saveNewRequest($student, $course, $semester){

		$this->load->model('request_model');
		
		$requisitionId = $this->request_model->saveNewRequest($student, $course, $semester);

		return $requisitionId;
	}
}
