<?php

require_once "WeightedPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class PreProjectEvaluation extends WeightedPhase{

	public function __construct($weight, $grade = FALSE, $id = FALSE, $startDate = FALSE, $endDate = FALSE, $knockoutPhase = FALSE){
		parent::__construct(SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE, $weight, $grade, $id, $startDate, $endDate, $knockoutPhase);
	}
}