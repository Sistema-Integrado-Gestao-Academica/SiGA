<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phase_model extends CI_Model {

	const PHASE_TABLE_NAME = "phase";
	
	const ID_ATTR = "id_phase";
	const NAME_ATTR = "phase_name";
	const WEIGHT_ATTR = "default_weight";

	public function getAllPhases() {
		
		$phases = $this->db->get(self::PHASE_TABLE_NAME)->result_array();

		$phases = checkArray($phases);

		return $phases;
	}
}