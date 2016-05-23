<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * In this class, consider where is written 'temp' equals to 'temporary'
 */
class TemporaryRequest extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('student/temporaryrequest_model');
	}

	public function confirmEnrollmentRequest($userId, $courseId, $semesterId){

		$userRequest = $this->getUserTempRequest($userId, $courseId, $semesterId);

		if($userRequest !== FALSE){


			$this->load->module("secretary/request");
			$result = $this->request->receiveStudentRequest($userRequest);

			if($result !== FALSE){
				$wasConfirmed = $this->cleanUserTempRequest($userId, $courseId, $semesterId);
			}else{
				$wasConfirmed = FALSE;
			}

		}else{
			$wasConfirmed = FALSE;
		}

		if($wasConfirmed){

			$status = "success";
			$message = "Solicitação de matrícula enviada com sucesso.";

			// If different of FALSE, $result is the disciplines of request which have problem to be saved
			if($result !== FALSE){

				if(sizeof($result) > 0){
					$status = "danger";

					$message = "Algumas solitações não foram atendidas.<br><br> Ocorreu um erro ao processar as matrículas das disciplinas abaixo:<br>";

					$this->load->model("program/discipline_model");
					foreach($result as $offerDiscipline){

						$foundDiscipline = $this->discipline_model->getDisciplineByCode($offerDiscipline['id_discipline']);
						$message = $message."<br>";
						$message = $message."{$foundDiscipline['discipline_name']}"." - Turma {$offerDiscipline['class']}";
					}

					$message = $message."<br><br>Contate o coordenador.";
				}
			}
		}else{
			$message = "Não foi possível confirmar sua matrícula, tente novamente.";
			$status = "danger";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function cleanUserTempRequest($userId, $courseId, $semesterId){

		$wasCleaned = $this->temporaryrequest_model->cleanUserTempRequest($userId, $courseId, $semesterId);

		return $wasCleaned;
	}

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$request = $this->temporaryrequest_model->getUserTempRequest($userId, $courseId, $semesterId);

		return $request;
	}

	public function addTempDisciplineToRequest($idOfferDiscipline, $courseId, $userId){

		// Semester data
		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();
		$semesterId = $currentSemester['id_semester'];

		$userTempRequest = $this->getUserTempRequest($userId, $courseId, $semesterId);

		$tryToSave = FALSE;
		if($userTempRequest !== FALSE){

			// Get the requested discipline hours
			$this->load->module("secretary/schedule");

			$this->schedule->getDisciplineHours($idOfferDiscipline);
			$requestedDisciplineSchedule = $this->schedule->getDisciplineSchedule();

			// Get disciplines hours from already inserted to resquest disciplines
			$insertedDisciplines = array();
			foreach($userTempRequest as $registeredRequest){

				$this->schedule->getDisciplineHours($registeredRequest['discipline_class']);
				$disciplineSchedule = $this->schedule->getDisciplineSchedule();

				$insertedDisciplines[] = $disciplineSchedule;
			}

			$conflicts = $this->schedule->checkHourConflits($requestedDisciplineSchedule, $insertedDisciplines);

			$tryToSave = FALSE;
			if($conflicts !== FALSE){
				$status = "danger";
				$message = "Não foi possível adicionar a disciplina pedida porque houve conflito de horários com disciplinas já adicionadas.<br>
				<i>Conflito no horário <b>".$conflicts->getDayHourPair()."</b>.</i>";
			}else{
				$tryToSave = TRUE;
			}
		}else{
			// In this case there is no discipline added to temp request, so is not a problem to add
			$tryToSave = TRUE;
		}

		if($tryToSave){
			$requestWasSaved = $this->saveTempRequest($userId, $courseId, $semesterId, $idOfferDiscipline);

			if($requestWasSaved){
				$status = "success";
				$message = "Disciplina adicionada com sucesso à solicitação";
			}else{
				$status = "danger";
				$message = "Não foi possível adicionar a disciplina informada.
							 Cheque os dados informados e tente novamente.<br>
							 Não é possível adicionar a mesma turma de uma disciplina várias vezes.";
			}
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function saveTempRequest($userId, $courseId, $semesterId, $idOfferDiscipline){


		$tempRequest = array(
			'id_student' => $userId,
			'id_course' => $courseId,
			'id_semester' => $semesterId,
			'discipline_class' => $idOfferDiscipline
		);

		$foundRequest = $this->temporaryrequest_model->getTempRequest($tempRequest);

		// In this case the chosen class was not picked yet
		if($foundRequest === FALSE){
			$requestWasSaved = $this->temporaryrequest_model->saveTempRequest($tempRequest);
		}else{
			$requestWasSaved = FALSE;
		}

		return $requestWasSaved;
	}

	public function removeDisciplineFromTempRequest($userId, $courseId, $semesterId, $disciplineId, $disciplineClass){

		$this->load->model("secretary/offer_model");
		$foundOffer = $this->offer_model->getOfferBySemesterAndCourse($semesterId, $courseId);

		if($foundOffer !== FALSE){
			$offerDiscipline = $this->offer_model->getCourseOfferDisciplineByClass($disciplineId, $foundOffer['id_offer'], $disciplineClass);
		}else{
			$offerDiscipline = FALSE;
		}

		if($offerDiscipline !== FALSE){

			$idOfferDiscipline = $offerDiscipline['id_offer_discipline'];

			$requestToRemove = array(
				'id_student' => $userId,
				'id_course' => $courseId,
				'id_semester' => $semesterId,
				'discipline_class' => $idOfferDiscipline
			);

			$requestWasRemoved = $this->removeTempRequest($requestToRemove);

			if($requestWasRemoved){
				$status = "success";
				$message = "Disciplina removida com sucesso da solicitação.";
			}else{
				$status = "danger";
				$message = "Não foi possível remover a disciplina. Cheque os dados informados e tente novamente.";
			}

		}else{
			$status = "danger";
			$message = "Não foi possível remover a disciplina. Cheque os dados informados e tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function removeTempRequest($requestToRemove){

		$requestWasRemoved = $this->temporaryrequest_model->removeTempRequest($requestToRemove);

		return $requestWasRemoved;
	}
}
