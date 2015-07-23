<?php

class Mastermind_model extends CI_Model {
	
	public function relateMastermindToStudent($saveRelation){
		
		$saved = $this->db->insert('mastermind_student', $saveRelation);
		
		if($saved){
			$savingSuccess = TRUE;
		}else{
			$savingSuccess = FALSE;
		}
		
		return $savingSuccess;
	}
	
	public function removeMastermindStudentRelation($lineToRemove){
		
		$removed = $this->db->delete('mastermind_student', $lineToRemove);
		
		if ($removed){
			$removedOk = TRUE;
		}else {
			$removedOk = FALSE;
		}
		
		return $removedOk;
	}
	
	public function getMastermindStudentRelations(){
		$this->db->select('id_mastermind,id_student');
		$masermindsAndStudants = $this->db->get('mastermind_student')->result_array();
		$masermindsAndStudants = checkArray($masermindsAndStudants);
		return $masermindsAndStudants;
		
	}
	
	public function getStutentsByIdMastermind($idMastermind){
		$this->db->select('id_student');
		$this->db->where('id_mastermind', $idMastermind);
		$students = $this->db->get('mastermind_student')->result_array();
		$students = checkArray($students);
		return $students;
	}
	
	public function updateTitlingArea($userId, $titlingArea, $tiling_thesis){
		
		$registerExists = $this->checkTitlingRegistExists($userId);
		
		if ($registerExists){
			$updated = $this->updateMastermindTitlingArea($userId, $titlingArea, $tiling_thesis);
		}else{
			$updated = $this->registerMAstermindTitlingArea($userId, $titlingArea, $tiling_thesis);
		}

		return $updated;
	}

	public function getCurrentArea($userId){
		
		$area = $this->db->get_where('mastermind_titling_area', array('id_mastermind'=>$userId))->row_array();
		
		$exists = checkArray($area);
		
		if ($exists){
			return $exists['id_program_area'];
		}else{
			return $exists;
		}
	}
	
	private function checkTitlingRegistExists($userId){
	
		$exists = $this->db->get_where('mastermind_titling_area', array('id_mastermind'=>$userId))->row_array();
	
		$registerExists = checkArray($exists);
	
		return $registerExists;
	
	}
	
	private function updateMastermindTitlingArea($userId, $titlingArea, $tiling_thesis){
		
		$dataToUpdate = array(
			'id_program_area' => $titlingArea,
			'doctorate_thesis' => $tiling_thesis
		);
		
		$this->db->where("id_mastermind", $userId);
		$updated = $this->db->update('mastermind_titling_area', $dataToUpdate);
		
		return $updated;
		
	}
	
	private function registerMAstermindTitlingArea($userId, $titlingArea, $tiling_thesis){
		
		$dataToSave = array(
			'id_mastermind' => $userId,
			'id_program_area' => $titlingArea,
			'doctorate_thesis' => $tiling_thesis
		);
		
		$inserted = $this->db->insert('mastermind_titling_area', $dataToSave);
		
		return $inserted;
		
	}
	
}