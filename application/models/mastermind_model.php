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
}