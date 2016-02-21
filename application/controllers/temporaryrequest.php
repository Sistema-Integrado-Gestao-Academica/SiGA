<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('request.php');
require_once('offer.php');
require_once('discipline.php');
require_once('semester.php');
require_once('schedule.php');

/**
 * In this class, consider where is written 'temp' equals to 'temporary'
 */
class TemporaryRequest extends CI_Controller {

	public function confirmEnrollmentRequest($userId, $courseId, $semesterId){

		$userRequest = $this->getUserTempRequest($userId, $courseId, $semesterId);

		if($userRequest !== FALSE){

			$this->load->model('temporaryrequest_model');

			$request = new Request();

			$result = $request->receiveStudentRequest($userRequest);

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

					$discipline = new Discipline();
					foreach($result as $offerDiscipline){

						$foundDiscipline = $discipline->getDisciplineByCode($offerDiscipline['id_discipline']);
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

		$this->session->set_flashdata($status, $message);

		redirect("request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function cleanUserTempRequest($userId, $courseId, $semesterId){

		$this->load->model('temporaryrequest_model');

		$wasCleaned = $this->temporaryrequest_model->cleanUserTempRequest($userId, $courseId, $semesterId);

		return $wasCleaned;
	}

	public function getUserTempRequest($userId, $courseId, $semesterId){

		$this->load->model('temporaryrequest_model');

		$request = $this->temporaryrequest_model->getUserTempRequest($userId, $courseId, $semesterId);

		return $request;
	}

	// Used by ajax request
	public function searchDisciplinesToRequest(){

		$disciplineName = $this->input->post('disciplineName');
		$courseId = $this->input->post('courseId');
		$userId = $this->input->post('userId');

		// Semester data
		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();
		$semesterId = $currentSemester['id_semester'];

		// Offer data
		$offer = new Offer();
		$courseOffer = $offer->getOfferBySemesterAndCourse($semesterId, $courseId);

		if($courseOffer !== FALSE){

			$offerId = $courseOffer['id_offer'];

			$discipline = new Discipline();
			$disciplinesClasses = $discipline->getClassesByDisciplineName($disciplineName, $offerId);

		}else{
			$disciplineClasses = FALSE;
		}

		if($disciplinesClasses !== FALSE){

			echo "<div class=\"box-body table-responsive no-padding\">";
			echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

				echo "<tr>";
				echo "<th class=\"text-center\">Código</th>";
				echo "<th class=\"text-center\">Disciplina</th>";
				echo "<th class=\"text-center\">Turma</th>";
				echo "<th class=\"text-center\">Vagas restantes</th>";
				echo "<th class=\"text-center\">Ações</th>";
				echo "</tr>";

					foreach($disciplinesClasses as $class){
						echo "<tr>";
							echo "<td>";
							echo $class['id_offer_discipline'];
							echo "</td>";

							echo "<td>";
							echo $class['discipline_name']."-".$class["name_abbreviation"];
							echo "</td>";

							echo "<td>";
							echo $class['class'];
							echo "</td>";

							echo "<td>";
							echo $class['current_vacancies'];
							echo "</td>";

							echo "<td>";
							echo anchor("temporaryrequest/addTempDisciplineToRequest/{$class['id_offer_discipline']}/{$courseId}/{$userId}","Adicionar à matrícula", "class='btn btn-primary'");
							echo "</td>";
						echo "</tr>";
					}

			echo "</tbody>";
			echo "</table>";
			echo "</div>";

		}else{

			echo "<div class='callout callout-info'>";
			echo "<h4>Não foram encontradas disciplinas com o nome '".$disciplineName."' para a oferta do semestre atual.</h4>";
			echo "</div>";
		}
	}

	public function addTempDisciplineToRequest($idOfferDiscipline, $courseId, $userId){

		$this->load->model('temporaryrequest_model');

		// Semester data
		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();
		$semesterId = $currentSemester['id_semester'];

		$userTempRequest = $this->getUserTempRequest($userId, $courseId, $semesterId);

		$tryToSave = FALSE;
		if($userTempRequest !== FALSE){

			// Get the requested discipline hours
			$schedule = new Schedule();
			$schedule->getDisciplineHours($idOfferDiscipline);
			$requestedDisciplineSchedule = $schedule->getDisciplineSchedule();

			// Get disciplines hours from already inserted to resquest disciplines
			$insertedDisciplines = array();
			foreach($userTempRequest as $registeredRequest){

				$schedule = new Schedule();
				$schedule->getDisciplineHours($registeredRequest['discipline_class']);
				$disciplineSchedule = $schedule->getDisciplineSchedule();

				$insertedDisciplines[] = $disciplineSchedule;
			}

			$conflicts = $schedule->checkHourConflits($requestedDisciplineSchedule, $insertedDisciplines);

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

		$this->session->set_flashdata($status, $message);

		redirect("request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function saveTempRequest($userId, $courseId, $semesterId, $idOfferDiscipline){

		$this->load->model('temporaryrequest_model');

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

		$this->load->model('temporaryrequest_model');

		$offer = new Offer();
		$foundOffer = $offer->getOfferBySemesterAndCourse($semesterId, $courseId);

		if($foundOffer !== FALSE){
			$offerDiscipline = $offer->getCourseOfferDisciplineByClass($disciplineId, $foundOffer['id_offer'], $disciplineClass);
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

		$this->session->set_flashdata($status, $message);

		redirect("request/studentEnrollment/{$courseId}/{$userId}");
	}

	private function removeTempRequest($requestToRemove){

		$this->load->model('temporaryrequest_model');

		$requestWasRemoved = $this->temporaryrequest_model->removeTempRequest($requestToRemove);

		return $requestWasRemoved;
	}
}
