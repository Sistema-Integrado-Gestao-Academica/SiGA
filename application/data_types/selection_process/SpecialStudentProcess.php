<?php

require_once "SelectionProcess.php";

class SpecialStudentProcess extends SelectionProcess{
	
	public function __construct($course = FALSE, $name = "", $id = FALSE){
		parent::__construct($course, $name);
	}

	public function getType(){
		return SelectionProcessConstants::REGULAR_STUDENT;
	}
}