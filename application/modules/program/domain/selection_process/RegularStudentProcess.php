<?php

require_once "SelectionProcess.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");

class RegularStudentProcess extends SelectionProcess{
	
	public function __construct($course = FALSE, $name = "", $id = FALSE, $vacancies, $status = FALSE){
		parent::__construct($course, $name, $id, $vacancies, $status);
	}

	public function getType(){
		return SelectionProcessConstants::REGULAR_STUDENT;
	}

	public function getFormmatedType(){
		return SelectionProcessConstants::REGULAR_STUDENT_PORTUGUESE;
	}
}