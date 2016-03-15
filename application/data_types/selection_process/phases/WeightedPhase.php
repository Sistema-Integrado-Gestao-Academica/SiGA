<?php

require_once "ProcessPhase.php";

abstract class WeightedPhase extends ProcessPhase{
	
	protected $weight;
	protected $grade;

	public function __construct($phaseName, $weight, $grade = FALSE){
		parent::__construct($phaseName);
		$this->setWeight($weight);
		$this->setGrade($grade);
	}

	private function setWeight($weight){
		$this->weight = $weight;
	}

	private function setGrade($grade){
		$this->grade = $grade;
	}

	public function getWeight(){
		return $this->weight;
	}

	public function getGrade(){
		return $this->grade;
	}
}