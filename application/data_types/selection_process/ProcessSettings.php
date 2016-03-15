<?php

require_once APPPATH."/exception/SelectionProcessException.php";
require_once "phases/ProcessPhase.php";

class ProcessSettings{

	const INVALID_PHASE = "As fases do processo seletivo não pode ser nulas."; 

	private $startDate;
	private $endDate;
	private $phases;
	private $phasesOrder;
	private $currentPhase;

	public function __construct($startDate, $endDate, $phases = FALSE, $phasesOrder = FALSE, $currentPhase = FALSE){

		$this->setStartDate($startDate);
		$this->setEndDate($endDate);
		$this->setPhases($phases);
		$this->setPhasesOrder($phasesOrder);
		$this->setCurrentPhase($currentPhase);
	}

	public function addPhase($phase){

		/**
		 
		 Ver questão da classe abstrata

		 **/

		if(get_parent_class($phase) === ProcessPhase::class){
			
			if(!is_null($phase)){
				$this->phases[] = $phase;
			}else{
				throw new SelectionProcessException(self::INVALID_PHASE);			
			}
		}else{
			throw new SelectionProcessException(self::INVALID_PHASE);
		}
	}

//Setters

	private function setStartDate($startDate){

		$this->startDate = $startDate;
	}

	private function setEndDate($endDate){
		
		$this->endDate = $endDate;
	}

	private function setPhases($phases){

		if($phases !== FALSE){
			$this->phases = $phases;
		}else{
			$this->phases = array();
		}
	}

	private function setPhasesOrder($phasesOrder){

		if($phasesOrder !== FALSE){
			$this->phasesOrder = $phasesOrder;
		}else{
			$this->phasesOrder = array();
		}
	}

	private function setCurrentPhase($currentPhase){

		if($currentPhase !== FALSE){
			$this->currentPhase = $currentPhase;
		}else{

		}
	}

// Getters

	public function getStartDate(){
		return $this->startDate;
	}
	
	public function getEndDate(){
		return $this->endDate;
	}

	public function getPhases(){
		return $this->phases;
	}

	public function getPhasesOrder(){
		return $this->phasesOrder;
	}

	public function getCurrentPhase(){
		return $this->currentPhase;
	}
}