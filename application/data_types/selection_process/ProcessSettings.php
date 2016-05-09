<?php

require_once APPPATH."/exception/SelectionProcessException.php";
require_once "phases/ProcessPhase.php";
require_once "phases/WeightedPhase.php";

class ProcessSettings{

	const INVALID_PHASE = "As fases do processo seletivo não pode ser nulas."; 
	const INVALID_START_DATE = "A data inicial informada é inválida. Deve estar no formato dd/mm/yyyy.";
	const INVALID_END_DATE = "A data final informada é inválida. Deve estar no formato dd/mm/yyyy.";
	const INVALID_DATE_INTERVAL = "A data final não pode ser antes ou igual à data inicial.";

	private $startDate;
	private $endDate;
	private $phases;
	private $phasesOrder;

	public function __construct($startDate, $endDate, $phases = FALSE, $phasesOrder = FALSE){

		$this->setStartDate($startDate);
		$this->setEndDate($endDate);
		$this->validateDatesDiff();
		$this->setPhases($phases);
		$this->setPhasesOrder($phasesOrder);
	}

	public function addPhase($phase){

		$parentClass = get_parent_class($phase);
		if($parentClass === ProcessPhase::class || $parentClass === WeightedPhase::class){
			
			if(!is_null($phase)){
				$this->phases[] = $phase;
			}else{
				throw new SelectionProcessException(self::INVALID_PHASE);			
			}
		}else{
			throw new SelectionProcessException(self::INVALID_PHASE);
		}
	}

	private function validateDatesDiff(){

		$startDate = $this->getStartDate();
		$endDate = $this->getEndDate();

		// The end date must be later than the start date
		if($endDate <= $startDate){
			throw new SelectionProcessException(self::INVALID_DATE_INTERVAL);
		}
	}

//Setters

	private function setStartDate($startDate){

		$date = $this->validateDate($startDate);

	   if($date !== FALSE){

	   		$validStartDate = $this->formatDateToDateTime($date);

			try{
				$validDate = new DateTime($validStartDate);
				
				$this->startDate = $validDate;
			}catch(Exception $e){
				
				throw new SelectionProcessException("Data informada: '".$startDate."' - ".self::INVALID_START_DATE." - ".$e->getMessage());
			}
	   }else{
			throw new SelectionProcessException("Data informada: '".$startDate."' - ".self::INVALID_START_DATE);
	   }
	   
	}

	private function setEndDate($endDate){
		
		$date = $this->validateDate($endDate);

	   if($date !== FALSE){

	   		$validStartDate = $this->formatDateToDateTime($date);

			try{
				$validDate = new DateTime($validStartDate);
				
				$this->endDate = $validDate;
			}catch(Exception $e){
				
				throw new SelectionProcessException("Data informada: '".$endDate."' - ".self::INVALID_END_DATE." - ".$e->getMessage());
			}
	   }else{
			throw new SelectionProcessException("Data informada: '".$endDate."' - ".self::INVALID_END_DATE);
	   }
	}

	private function validateDate($strDate){

		$date = date_parse_from_format("d/m/Y", $strDate);

		$dateIsValid = $date["year"] !== FALSE && $date["month"] !== FALSE 
					   && $date["day"] !== FALSE && $date["error_count"] === 0 
					   && $date["warning_count"] === 0;

		if(!$dateIsValid){
			$date = FALSE;
		}

		return $date;
	}

	private function formatDateToDateTime($date){

		$day = $date["day"];
		$month = $date["month"];
		$year = $date["year"];

		$strDay = (string) $day;
		$strMonth = (string) $month;
		
		if(strlen($strDay) === 1){
			$day = "0".$day;
		}

		if(strlen($strMonth) === 1){
			$month = "0".$month;
		}

		// Valid format date to DateTime class
		$formattedDate = $year."/".$month."/".$day;

		return $formattedDate;
	}

	private function setPhases($phases){

		if($phases !== FALSE){

			if(is_array($phases)){

				foreach($phases as $phase){
					
					$this->addPhase($phase);
				}

			}else{
				throw new SelectionProcessException(self::INVALID_PHASE);
			}
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

// Getters

	public function getStartDate(){
		return $this->startDate;
	}

	public function getYMDStartDate(){
		
		$date = $this->getStartDate();

		$formattedDate = $date->format("Y/m/d");

		return $formattedDate;
	}

	public function getYMDEndDate(){
		
		$date = $this->getEndDate();

		$formattedDate = $date->format("Y/m/d");

		return $formattedDate;
	}

	public function getFormattedStartDate(){
		
		$date = $this->getStartDate();

		$formattedDate = $date->format("d/m/Y");

		return $formattedDate;
	}

	public function getFormattedEndDate(){
		
		$date = $this->getEndDate();

		$formattedDate = $date->format("d/m/Y");

		return $formattedDate;
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
}