<?php

require_once "SelectionProcess.php";

class SpecialStudentProcess extends SelectionProcess{
	
	public function __construct($course = FALSE, $name = "", $id = FALSE, $vacancies, $status = FALSE){
		parent::__construct($course, $name, $id, $vacancies, $status);
	}

	public function getType(){
		return SelectionProcessConstants::SPECIAL_STUDENT;
	}

	public function getFormmatedType(){
		return SelectionProcessConstants::SPECIAL_STUDENT_PORTUGUESE;
	}
}