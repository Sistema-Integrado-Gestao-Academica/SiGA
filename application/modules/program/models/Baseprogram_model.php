<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/domain/Group.php");

class BaseProgram_model extends CI_Model {

	protected $TABLE = "base_program_evaluation";

	public function getBasePrograms(){

		$basePrograms = $this->get(FALSE, FALSE, FALSE);

		return $basePrograms;
	}
}
