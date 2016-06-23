<?php 

require_once MODULESPATH.'finantial/exception/ExpenseException.php';

class ExpenseDetail{

	const INVALID_DATE = "Data inválida";
	const VALUE_CANT_BE_NULL = "O valor deve ser preenchido.";
	const VALUE_CANT_BE_NEGATIVE = "O valor não pode ser negativo";

	private $note;
	private $emissionDate;
	private $seiProcess;
	private $value;
	private $description;

	public function __construct($note = FALSE, $emissionDate = FALSE, 
								$seiProcess = FALSE, $value, $description = FALSE){
		
		$this->setNote($note);
		$this->setEmissionDate($emissionDate);
		$this->setSEIProcess($seiProcess);
		$this->setValue($value);
		$this->setDescription($description);

	}

	private function setNote($note){

		$this->note = $note;
	}

	private function setEmissionDate($emissionDate){
		
		if($emissionDate !== FALSE && !is_null($emissionDate) && !empty($emissionDate)){

			$validDate = $this->validateDate($emissionDate);

			if($validDate){
				$date = $this->formatDateToDateTime($validDate);
				$emissionDate = new DateTime($date); 
				$this->emissionDate = $emissionDate;
			}
			else{
				throw new ExpenseException(self::INVALID_DATE);
			}
		}
		else{
			$this->emissionDate = $emissionDate;
		}


	}

	private function setSEIProcess($seiProcess){
		
		$this->seiProcess = $seiProcess;
	
	}

	private function setValue($value){
		
		if($value !== FALSE){

			if(!is_null($value) && !empty($value)){

				if($value > 0.0){
					$this->value = $value;
				}
				else{
					throw new ExpenseException(self::VALUE_CANT_BE_NEGATIVE);
				}
			}
			else{
				throw new ExpenseException(self::VALUE_CANT_BE_NULL);
			}
		}
		else{
			$this->value = $value;
		}
	}


	private function setDescription($description){

		$this->description = $description;
	
	}

	private function validateDate($emissionDate){

		$date = date_parse_from_format("d/m/Y", $emissionDate);

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



	public function getNote(){
		return $this->note;
	}

	public function getEmissionDate(){
		return $this->emissionDate;
	}

	public function getYMDEmissionDate(){
		
		$date = $this->getEmissionDate();

		$formattedDate = $date->format("Y/m/d");

		return $formattedDate;
	}

	public function getSEIProcess(){
		return $this->seiProcess;
	}

	public function getValue(){
		return $this->value;
	}
}