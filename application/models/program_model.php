<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program_model extends CI_Model {

	public function getAllPrograms(){

		$allPrograms = $this->db->get('program')->result_array();

		if(sizeof($allPrograms) > 0){
			// Nothing to do
		}else{
			$allPrograms = FALSE;
		}

		return $allPrograms;
	}

	public function saveProgram($program){

		$wasSaved = $this->insertProgram($program);

		return $wasSaved;
	}

	private function insertProgram($program){
		
		$this->db->insert('program', $program);

		$insertedProgram = $this->getProgram($program);

		if($insertedProgram !== FALSE){
			$wasSaved = TRUE;
		}else{
			$wasSaved = FALSE;
		}

		return $wasSaved;
	}

	private function getProgram($programToSearch){

		$foundProgram = $this->db->get_where('program', $programToSearch)->result_array();

		if(sizeof($foundProgram) > 0){
			// Nothing to do
		}else{
			$foundProgram = FALSE;
		}

		return $foundProgram;
	}
}
