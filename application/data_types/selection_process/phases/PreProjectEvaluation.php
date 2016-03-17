<?php

require_once "WeightedPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class PreProjectEvaluation extends WeightedPhase{

	public function __construct($phaseName, $weight, $grade = FALSE){
		parent::__construct($phaseName, $weight, $grade);
	}
}