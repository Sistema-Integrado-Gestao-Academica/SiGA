<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Semester_model extends CI_Model {

	public function getCurrentSemester(){

		$this->db->select('semester.*');
		$this->db->from('semester');
		$this->db->join('current_semester', 'current_semester.id_semester = semester.id_semester');
		$currentSemester = $this->db->get()->row_array();

		$currentSemester = checkArray($currentSemester);

		return $currentSemester;
	}

	public function updateCurrentSemester($newCurrentSemester){

		$semesterExists = $this->checkIfSemesterExists($newCurrentSemester);

		if($semesterExists){
			
			$currentSemester = array('id_semester' => $newCurrentSemester);
			$this->db->update('current_semester', $currentSemester);

			$semesterWasUpdated = TRUE;
		}else{
			$semesterWasUpdated = FALSE;
		}

		return $semesterWasUpdated;
	}

	private function checkIfSemesterExists($semesterId){

		$searchResult = $this->db->get_where('semester', array('id_semester' => $semesterId));
		$foundSemester = $searchResult->row_array();

		$semesterExists = sizeof($foundSemester) > 0;

		return $semesterExists;
	}

	public function getNextSemester(){

		$currentSemester = $this->getCurrentSemester(); 

		$this->db->select('semester.*');
		$this->db->from('semester');
		$this->db->where('id_semester', ($currentSemester['id_semester'] + 1));
		$nextSemester = $this->db->get()->row_array();

		$nextSemester = checkArray($nextSemester);

		return $nextSemester;

	}
}