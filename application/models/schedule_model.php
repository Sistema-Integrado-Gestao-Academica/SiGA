<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/ClassHour.php");
require_once(APPPATH."/exception/ClassHourException.php");
require_once(APPPATH."/exception/ScheduleException.php");

class Schedule_model extends CI_Model {

	public function updateClassLocal($idOfferDiscipline, $idClassHour, $newClassLocal){

		$disciplineClassHour = array(
			'id_offer_discipline' => $idOfferDiscipline,
			'id_class_hour' => $idClassHour
		);

		$foundClassHour = $this->getDisciplineClassHour($disciplineClassHour);

		if($foundClassHour !== FALSE){

			$this->db->where('id_offer_discipline', $idOfferDiscipline);
			$this->db->where('id_class_hour', $idClassHour);
			$this->db->update('discipline_schedule', array('class_local' => $newClassLocal));
				
			$disciplineClassHour['class_local'] = $newClassLocal;
			$foundClassHour = $this->getDisciplineClassHour($disciplineClassHour);

			if($foundClassHour !== FALSE){
				$wasUpdated = TRUE;
			}else{
				$wasUpdated = FALSE;
			}

		}else{
			$wasUpdated = FALSE;
		}

		return $wasUpdated;
	}

	/**
	 * Save a single class hour to a discipline class
	 * @param $classHour - ClassHour object that contains the class hour data 
	 * @param $idOfferDiscipline - Offer discipline id (references to offer_discipline table)
	 * @return TRUE if was saved, or FALSE if it was not
	 */
	public function saveClassHour($classHour, $idOfferDiscipline){

		$classHourData = $classHour->getClassHour();

		$hour = $classHourData['hour'];
		$day = $classHourData['day'];
		$local = $classHourData['local'];

		$foundClassHour = $this->getClassHourPair($hour, $day);

		if($foundClassHour !== FALSE){

			$classHourId = $foundClassHour['id_class_hour'];

			$schedule = array(
				'id_offer_discipline' => $idOfferDiscipline,
				'id_class_hour' => $classHourId,
				'class_local' => $local
			);

			$foundSchedule = $this->getDisciplineClassHour($schedule);

			// In this case there is not a tuple with these data
			$disciplineClassHourNotExistsYet = $foundSchedule === FALSE;
			if($disciplineClassHourNotExistsYet){

				$this->db->insert('discipline_schedule', $schedule);

				$foundSchedule = $this->getDisciplineClassHour($schedule);

				if($foundSchedule !== FALSE){
					$wasSaved = TRUE;
				}else{
					$wasSaved = FALSE;
				}
			}else{
				$wasSaved = FALSE;
			}

		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	public function getDisciplineHours($idOfferDiscipline){

		$this->db->select('class_hour.*, discipline_schedule.class_local');
		$this->db->from('class_hour');
		$this->db->join('discipline_schedule', "class_hour.id_class_hour = discipline_schedule.id_class_hour");
		$this->db->where('discipline_schedule.id_offer_discipline', $idOfferDiscipline);
		$disciplineHours = $this->db->get()->result_array();

		$disciplineHours = checkArray($disciplineHours);

		return $disciplineHours;
	}

	private function getDisciplineClassHour($disciplineClassHourData){

		$foundClassHour = $this->db->get_where('discipline_schedule', $disciplineClassHourData)->row_array();

		$foundClassHour = checkArray($foundClassHour);

		return $foundClassHour;
	}

	private function getClassHourPair($hour, $day){

		$classHour = $this->db->get_where('class_hour', array('hour' => $hour, 'day' => $day))->row_array();

		$classHour = checkArray($classHour);

		return $classHour;
	}

	public function getClassHourInSchedule($idOfferDiscipline, $hour, $day){

		$this->db->select('discipline_schedule.*');
		$this->db->from('discipline_schedule');
		$this->db->join('class_hour', "discipline_schedule.id_class_hour = class_hour.id_class_hour");
		$this->db->where('discipline_schedule.id_offer_discipline', $idOfferDiscipline);
		$this->db->where('class_hour.hour', $hour);
		$this->db->where('class_hour.day', $day);
		$foundClassHour = $this->db->get()->row_array();

		$foundClassHour = checkArray($foundClassHour);

		return $foundClassHour;
	}
	
	public function removeClassHourFromSchedule($idOfferDiscipline, $idClassHour){

		$disciplineClassHour = array(
			'id_offer_discipline' => $idOfferDiscipline,
			'id_class_hour' => $idClassHour
		);

		$foundClassHour = $this->getDisciplineClassHour($disciplineClassHour);

		if($foundClassHour !== FALSE){

			$this->db->delete('discipline_schedule', $disciplineClassHour);

			$foundClassHour = $this->getDisciplineClassHour($disciplineClassHour);

			if($foundClassHour !== FALSE){
				$wasRemoved = FALSE;
			}else{
				$wasRemoved = TRUE;
			}

		}else{
			$wasRemoved = TRUE;
		}

		return $wasRemoved;	
	}
}
