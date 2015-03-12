<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/ClassHour.php");
require_once(APPPATH."/exception/ClassHourException.php");
require_once(APPPATH."/exception/ScheduleException.php");

class Schedule extends CI_Controller {

	private $disciplineSchedule;

	const ERR_INVALID_OBJECT = "Required an ClassHour object as param";

	public function __construct(){

		$this->disciplineSchedule = array();
		parent::__construct();
	}

	public function getDisciplineSchedule(){
		return $this->disciplineSchedule;
	}

	public function drawFullSchedule($offerDiscipline){

		/* FIXING BUG*/
		/*This form tags was inserted because the first form inserted on the table was not being accepted*/
		echo form_open();
		echo form_close();
		/*DO NOT REMOVE IT*/

		echo "<div class=\"box-body table-responsive no-padding\">";
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

			    define("MAX_COLUMN", 6);
			    define("MAX_ROW", 9);
			    define("HOUR_INTERVAL_OF_CLASS", 2);
			    $hour = 6;
			    for($i = 1; $i <= MAX_ROW; $i++){
			    	
				    echo "<tr>";

					    for($j = 0; $j <= MAX_COLUMN; $j++){
					    	// First column
					    	if($j === 0){
					    		
							    echo "<td>";	
							    echo $hour."-".($hour + HOUR_INTERVAL_OF_CLASS);
							    echo "</td>";
							    $hour = $hour + 2;
					    	}else{
							    echo "<td>";
							    
							    	echo form_open("schedule/insertClassHour");
									    $hidden = array(
									    	'hour' => $i,
									    	'day' => $j,
									    	'idOfferDiscipline' => $offerDiscipline['id_offer_discipline'],
									    	'discipline' => $offerDiscipline['id_discipline'],
									    	'offer' => $offerDiscipline['id_offer'],
									    	'class' => $offerDiscipline['class']
									    );
									    echo form_hidden($hidden);

									    echo form_label("Local:", "classLocal");
									    echo form_input(array(
									    	"name" => "classLocal",
											"id" => "classLocal",
											"type" => "text",
											"class" => "form-campo",
											"class" => "form-control",
											"maxlength" => "15"
									    ));
									
										echo form_button(array(
											"class" => "btn btn-info btn-flat",
											"type" => "submit",
											"content" => "Adicionar horário"
										));
										    
									echo form_close();

								echo "</td>";
					    	}
					    }
				    echo "</tr>";
			    }
		    
			echo "</tbody>";
		echo "</table>";
		echo "</div>";
	}

	public function insertClassHour(){

		$hour = $this->input->post('hour');
		$day = $this->input->post('day');
		$local = $this->input->post('classLocal');

		$idOfferDiscipline = $this->input->post('idOfferDiscipline');
		$discipline = $this->input->post('discipline');
		$offer = $this->input->post('offer');
		$class = $this->input->post('class');

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

		$this->session->set_flashdata($status, $message);

		redirect("offer/formToUpdateDisciplineClass/{$offer}/{$discipline}/{$class}");
	}

	private function saveClassHour($classHour, $idOfferDiscipline){

		$this->load->model('schedule_model');

		$wasSaved = $this->schedule_model->saveClassHour($classHour, $idOfferDiscipline);

		return $wasSaved;
	}

	/**
	 * Add a class hour to the discipline schedule
	 * @param $classHour - ClassHour object that contains the class hour data
	 * @throws ScheduleException if the param is not an ClassHour object
	 */
	private function addClassHour($classHour){

		define("CLASS_HOUR_CLASS", "ClassHour");

		$objectClass = get_class($classHour);

		if($objectClass === CLASS_HOUR_CLASS){
			$this->disciplineSchedule[] = $classHour;
		}else{
			throw new ScheduleException(self::ERR_INVALID_OBJECT);
		}
	}

}
