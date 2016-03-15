<?php

require_once "WeightedPhase.php";

class WrittenTest extends WeightedPhase{
	
	public function __construct($phaseName, $weight, $grade = FALSE){
		parent::__construct($phaseName, $weight, $grade);
	}
	
}