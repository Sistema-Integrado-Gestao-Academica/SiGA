<?php 

require_once MODULESPATH.'finantial/exception/ExpenseException.php';

class Expense{

	const NOTE_CANT_BE_NULL = "A nota de empenho não pode estar vazia.";
	const DATE_CANT_BE_NULL = "A data de emissão não pode estar vazia.";
	const INVALID_DATE = "Data inválida";
	const SEI_PROCESS_CANT_BE_NULL = "O número do processo do SEI não pode estar vazio.";
	const VALUE_CANT_BE_NULL = "O valor não pode estar vazio.";
	const VALUE_CANT_BE_NEGATIVE = "O valor não pode ser negativo";
	
	private $note;
	private $emissionDate;
	private $seiProcess;
	private $value;

	public function __construct($note, $emissionDate = FALSE, $seiProcess = FALSE, $value = FALSE){
		
		$this->setNote($note);
		$this->setEmissionDate($emissionDate);
		$this->setSEIProcess($seiProcess);
		$this->setValue($value);

	}

	private function setNote($note){

		if(!empty($note) && !is_null($note)){
			$this->note = $note;
		}
		else{
			throw new ExpenseException(self::NOTE_CANT_BE_NULL);
			
		}

	}

	private function setEmissionDate($emissionDate){
		
		if($emissionDate !== FALSE){

			if(!empty($emissionDate) && !is_null($emissionDate)){
				$validDate = $this->validateDate($emissionDate);

				if($validDate){
					$date = $this->formatDateToDateTime($emissionDate);
					$emissionDate = new DateTime($date); 
					$this->emissionDate = $emissionDate;
				}
				else{
					throw new ExpenseException(self::INVALID_DATE);
				}
			}
			else{
				throw new ExpenseException(self::DATE_CANT_BE_NULL);
			}
		}
		else{
			$this->emissionDate = $emissionDate;
		}


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

	private function setSEIProcess($seiProcess){
		
		if($seiProcess !== FALSE){

			if(!empty($seiProcess) && !is_null($seiProcess)){				
				$this->seiProcess = $seiProcess;
			}
			else{
				throw new ExpenseException(self::SEI_PROCESS_CANT_BE_NULL);
			}
		}
		else{
			$this->seiProcess = $seiProcess;
		}
	}

	private function setValue($value){
		
		if($value !== FALSE){

			if(!empty($value) && !is_null($value)){				
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


}