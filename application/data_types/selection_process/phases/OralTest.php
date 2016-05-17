<?php

require_once "WeightedPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class OralTestOld extends WeightedPhase{
	
	public function __construct($weight, $grade = FALSE, $id = FALSE){
		parent::__construct(SelectionProcessConstants::ORAL_TEST_PHASE, $weight, $grade, $id);
	}
}