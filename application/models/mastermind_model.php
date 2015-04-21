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
}