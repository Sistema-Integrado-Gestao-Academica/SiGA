<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."secretary/domain/ClassHour.php");
require_once(MODULESPATH."secretary/exception/ClassHourException.php");
require_once(MODULESPATH."secretary/exception/ScheduleException.php");

class Schedule extends MX_Controller {

	private $disciplineSchedule;

	const HOUR_CONFLICT = "Occurred an hour conflict with the day hour pair passed";

	const ERR_INVALID_OBJECT = "Required an ClassHour object as param";

	public function __construct(){

		$this->disciplineSchedule = array();
		parent::__construct();
	}

	public function getDisciplineSchedule(){
		return $this->disciplineSchedule;
	}

	public function getDisciplineHours($idOfferDiscipline){

		$this->load->model('secretary/schedule_model');
		$disciplineHours = $this->schedule_model->getDisciplineHours($idOfferDiscipline);

		if($disciplineHours !== FALSE){

			foreach($disciplineHours as $disciplineHour){

				try{
					$classHour = new ClassHour($disciplineHour['hour'], $disciplineHour['day'], $disciplineHour['class_local']);

					$this->addClassHour($classHour);
				}catch(ClassHourException $caughtException){
					continue;
				}catch(ScheduleException $caughtException){
					continue;
				}
			}

		}else{
			// Nothing to do because the $disciplineSchedule will still being a empty array
		}
	}

	/**
	 * Add a class hour to the discipline schedule
	 * @param $classHour - ClassHour object that contains the class hour data
	 * @throws ScheduleException if the param is not an ClassHour object
	 */
	private function addClassHour($classHour){

		// Arbitrary object
		$ch = new ClassHour(1 , 1, "");
		$expectedClass = get_class($ch);

		$objectClass = get_class($classHour);

		if($objectClass === $expectedClass){
			$this->disciplineSchedule[] = $classHour;
		}else{
			throw new ScheduleException(self::ERR_INVALID_OBJECT);
		}
	}

	/**
	 * Check if occur hour conflicts when trying to add a discipline to request
	 * @param $requestedDisciplineSchedule
	 * @param $registeredDisciplinesSchedules
	 * @return a ClassHour object with the hour and day where occurred the conflict, or FALSE if did not occur a conflict
	 */
	public function checkHourConflits($requestedDisciplineSchedule, $registeredDisciplinesSchedules){

		$fullSchedule = $this->createFullSchedule();

		// At first time fill the schedule with the disciplines already added to request
		$fullSchedule = $this->fillWithRegisteredDisciplines($fullSchedule, $registeredDisciplinesSchedules);

		$conflict = FALSE;
		foreach($requestedDisciplineSchedule as $classHour){

			try{
				$fullSchedule = $this->fillSchedule($fullSchedule, $classHour);
			}catch(ScheduleException $caughtException){
				// Receive the class hour where occurs the conflict
				$conflict = $classHour;
				break;
			}
		}

		return $conflict;
	}

	private function fillWithRegisteredDisciplines($fullSchedule, $disciplinesSchedules){

		foreach($disciplinesSchedules as $schedule){

			foreach($schedule as $classHour){

				try{

					$fullSchedule = $this->fillSchedule($fullSchedule, $classHour);
				}catch(ScheduleException $caughtException){
					// In this case occured an error because the system accepted an hour conflict (What should not be done)
					continue;
				}
			}
		}

		return $fullSchedule;
	}

	/**
	 * Add a day hour pair on the schedule
	 * @param $fullSchedule - The schedule (6 x 6 matrix) to fill
	 * @param $classHour - ClassHour object to insert on the schedule
	 * @return the schedule with the day hour pair added
	 */
	private function fillSchedule($fullSchedule, $classHour){

		$classHourData = $classHour->getClassHour();

		$hour = $classHourData['hour'];
		$day = $classHourData['day'];

		// Correcting indexes numbers because the full schedule starts at 0
		$hour--;
		$day--;

		$isNotFilled = $fullSchedule[$hour][$day] == 0;

		if($isNotFilled){
			$fullSchedule[$hour][$day] = 1;
		}else{
			// In this case occurs an hour conflict
			throw new ScheduleException(self::HOUR_CONFLICT);
		}

		return $fullSchedule;
	}

	/**
	 * Create a full schedule matrix (9 x 6)
	 * @return a bidimensional array with all values equal to zero
	 */
	private function createFullSchedule(){

		$schedule = array(array());

		for($i = 0; $i < ClassHour::MAX_HOUR; $i++){
			for($j = 0; $j < ClassHour::MAX_DAY; $j++){

				$schedule[$i][$j] = 0;
			}
		}

		return $schedule;
	}

	public function drawFullSchedule($offerDiscipline, $idCourse){

		/* FIXING BUG*/
		/*This form tags was inserted because the first form inserted on the table was not being accepted*/
		echo form_open();
		echo form_close();
		/*DO NOT REMOVE IT*/

		echo "<div id='class_hour_table' class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Hora/Dia</th>";
			        echo "<th class=\"text-center\">Segunda</th>";
			        echo "<th class=\"text-center\">Terça</th>";
			        echo "<th class=\"text-center\">Quarta</th>";
			        echo "<th class=\"text-center\">Quinta</th>";
			        echo "<th class=\"text-center\">Sexta</th>";
			        echo "<th class=\"text-center\">Sábado</th>";
			    echo "</tr>";

			    define("MAX_COLUMN", ClassHour::MAX_DAY);
			    define("MAX_ROW", ClassHour::MAX_HOUR);
			    define("HOUR_INTERVAL_OF_CLASS", 2);

			    // Consider 'i x j' as 'line x column'
			    for($i = 1; $i <= MAX_ROW; $i++){

				    echo "<tr>";

					    for($j = 0; $j <= MAX_COLUMN; $j++){
					    	// First column
					    	if($j === 0){

					    		$currentClassHour = new ClassHour($i, $j+1);

							    echo "<td>";
							    echo $currentClassHour->getHourPair();
							    echo "</td>";
					    	}else{
							    echo "<td>";

							    	$foundClassHour = $this->getClassHourInSchedule($offerDiscipline['id_offer_discipline'], $i, $j);

							    	$classHourIsOnSchedule = $foundClassHour !== FALSE;
							    	if($classHourIsOnSchedule){

							    		$idOfferDiscipline = $foundClassHour['id_offer_discipline'];
							    		$idClassHour = $foundClassHour['id_class_hour'];

							    		// Anchor to remove the class hour
							    		echo anchor(
							    			"secretary/schedule/removeClassHourFromSchedule/{$idOfferDiscipline}/{$idClassHour}/{$offerDiscipline['id_offer']}/{$offerDiscipline['id_discipline']}/{$offerDiscipline['class']}/{$idCourse}",
							    			"Remover Horário",
							    			"class='btn btn-danger btn-flat'"
							    		);

							    		// Form to update the class local
							    		echo form_open("secretary/schedule/changeClassLocal");
										    $hidden = array(
										    	'idOfferDiscipline' => $idOfferDiscipline,
										    	'idClassHour' => $idClassHour,
										    	'discipline' => $offerDiscipline['id_discipline'],
										    	'offer' => $offerDiscipline['id_offer'],
										    	'class' => $offerDiscipline['class'],
										    	'course' => $idCourse
										    );

										    $localClassInput = array(
										    	"name" => "newClassLocal",
												"id" => "newClassLocal",
												"type" => "text",
												"class" => "form-campo",
												"class" => "form-control",
												"maxlength" => "15"
										    );

										    if($foundClassHour['class_local'] !== NULL){
										    	$localClassInput['value'] = $foundClassHour['class_local'];
										    }else{
										    	$localClassInput['value'] = "";
										    	$localClassInput['placeholder'] = "Nenhum local adicionado";
										    }

										    echo form_hidden($hidden);

										    echo form_label("Local adicionado:", "classLocal");
										    echo form_input($localClassInput);

											echo form_button(array(
												"class" => "btn bg-navy btn-flat",
												"type" => "submit",
												"content" => "Alterar local"
											));

										echo form_close();

							    	}else{

								    	echo form_open("secretary/schedule/insertClassHour");
										    $hidden = array(
										    	'hour' => $i,
										    	'day' => $j,
										    	'idOfferDiscipline' => $offerDiscipline['id_offer_discipline'],
										    	'discipline' => $offerDiscipline['id_discipline'],
										    	'offer' => $offerDiscipline['id_offer'],
										    	'class' => $offerDiscipline['class'],
										    	'course' => $idCourse
										    );
										    echo form_hidden($hidden);

										    echo form_label("Local:", "classLocal");
										    echo form_input(array(
										    	"name" => "classLocal",
												"id" => "classLocal",
												"type" => "text",
												"class" => "form-campo",
												"class" => "form-control",
												"maxlength" => "15",
												"placeholder" => "Informe o local"
										    ));

											echo form_button(array(
												"class" => "btn btn-info btn-flat",
												"type" => "submit",
												"content" => "Adicionar horário"
											));

										echo form_close();
							    	}

								echo "</td>";
					    	}
					    }
				    echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
		echo "</div>";
	}

	public function changeClassLocal(){

		$idOfferDiscipline = $this->input->post('idOfferDiscipline');
		$idClassHour = $this->input->post('idClassHour');
		$newClassLocal = $this->input->post('newClassLocal');

		$discipline = $this->input->post('discipline');
		$offer = $this->input->post('offer');
		$class = $this->input->post('class');
		$idCourse = $this->input->post('course');

		$wasUpdated = $this->updateClassLocal($idOfferDiscipline, $idClassHour, $newClassLocal);

		if($wasUpdated){
			$status = "success";
			$message = "Local alterado com sucesso.";
		}else{
			$status = "danger";
			$message = "Ocorreu um erro e não foi possível alterar o local.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/offer/formToUpdateDisciplineClass/{$offer}/{$discipline}/{$class}/{$idCourse}");
	}

	private function updateClassLocal($idOfferDiscipline, $idClassHour, $newClassLocal){

		$this->load->model('secretary/schedule_model');

		$wasUpdated = $this->schedule_model->updateClassLocal($idOfferDiscipline, $idClassHour, $newClassLocal);

		return $wasUpdated;
	}

	public function removeClassHourFromSchedule($idOfferDiscipline, $idClassHour, $idOffer, $idDiscipline, $class, $courseId){

		$this->load->model('secretary/schedule_model');

		$wasRemoved = $this->schedule_model->removeClassHourFromSchedule($idOfferDiscipline, $idClassHour);

		if($wasRemoved){
			$status = "success";
			$message = "Horário retirado com sucesso.";
		}else{
			$status = "danger";
			$message = "Ocorreu um erro e não foi possível retirar o horário";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/offer/formToUpdateDisciplineClass/{$idOffer}/{$idDiscipline}/{$class}/{$courseId}");
	}

	public function insertClassHour(){

		$hour = $this->input->post('hour');
		$day = $this->input->post('day');
		$local = $this->input->post('classLocal');

		$idOfferDiscipline = $this->input->post('idOfferDiscipline');
		$discipline = $this->input->post('discipline');
		$offer = $this->input->post('offer');
		$class = $this->input->post('class');
		$idCourse = $this->input->post('course');

		try{

			$classHour = new ClassHour($hour, $day, $local);

			$wasSaved = $this->saveClassHour($classHour, $idOfferDiscipline);

			if($wasSaved){
				$status = "success";
				$message = "Horário adicionado.";
			}else{
				$status = "danger";
				$message = "Não foi possível adicionar esse horário, um erro inesperado ocorreu. Contate o administrador.";
			}

		}catch(ClassHourException $caughtException){
			$status = "danger";
			switch($caughtException->getMessage()){
				case ClassHour::ERR_INVALID_HOUR:
					$message = "Ocorreu um erro. A hora informada não é válida.";
					break;

				case ClassHour::ERR_INVALID_DAY:
					$message = "Ocorreu um erro. O dia informado não é válido.";
					break;

				case ClassHour::ERR_INVALID_LOCAL:
					$message = "O local informado não é válido.";
					break;

				default:
					$message = "Ocorreu um erro. Contate o administrador.";
					break;
			}
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);

		redirect("secretary/offer/formToUpdateDisciplineClass/{$offer}/{$discipline}/{$class}/{$idCourse}");
	}

	private function saveClassHour($classHour, $idOfferDiscipline){

		$this->load->model('secretary/schedule_model');

		$wasSaved = $this->schedule_model->saveClassHour($classHour, $idOfferDiscipline);

		return $wasSaved;
	}

	private function getClassHourInSchedule($idOfferDiscipline, $hour, $day){

		$this->load->model('secretary/schedule_model');

		$hourIsOnSchedule = $this->schedule_model->getClassHourInSchedule($idOfferDiscipline, $hour, $day);

		return $hourIsOnSchedule;
	}

}
