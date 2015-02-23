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
}
