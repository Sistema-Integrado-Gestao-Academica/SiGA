<?php

require_once "SelectionProcess.php";

class RegularStudentProcess extends SelectionProcess{
	
	public function __construct($course = FALSE, $name = "", $id = FALSE){
		parent::__construct($course, $name, $id);
	}
}