<?php

require_once('constants.php');

class TeacherConstants extends Constants{
	
	// Teacher possible situations
	const PERMANENT_SITUATION = "Permanente";
	const COLLABORATOR_SITUATION = "Colaborador";
	const SPECIFIC_ORIENTATION_SITUATION = "Orientação Específica";

	public function getSituations(){

		$situations = array(
			self::PERMANENT_SITUATION => self::PERMANENT_SITUATION,
			self::COLLABORATOR_SITUATION => self::COLLABORATOR_SITUATION,
			self::SPECIFIC_ORIENTATION_SITUATION => self::SPECIFIC_ORIENTATION_SITUATION
		);

		return $situations;
	}
}