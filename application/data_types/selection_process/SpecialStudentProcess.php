<?php

require_once "SelectionProcess.php";

class SpecialStudentProcessOld extends SelectionProcess{
	
	public function __construct($course = FALSE, $name = "", $id = FALSE){
		parent::__construct($course, $name);
	}

	public function getType(){
		return SelectionProcessConstants::SPECIAL_STUDENT;
	}

	public function getFormmatedType(){
		return SelectionProcessConstants::SPECIAL_STUDENT_PORTUGUESE;
	}
}