<?php
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

abstract class ProcessPhase{

	const INVALID_PHASE_NAME = "Fase inválida. Fases disponíveis: Homologação, Avaliação de Pré-Projeto, Prova escrita e Prova oral.";
	const INVALID_START_DATE = "A data inicial informada é inválida. Deve estar no formato dd/mm/yyyy.";
	const INVALID_END_DATE = "A data final informada é inválida. Deve estar no formato dd/mm/yyyy.";
	const INVALID_DATE_INTERVAL = "A data final não pode ser antes ou igual à data inicial.";


	protected $id;
	protected $phaseName;
	protected $startDate;
	protected $endDate;

	public function __construct($phaseName, $id = FALSE, $startDate = FALSE, $endDate = FALSE){
		$this->setPhaseName($phaseName);
		$this->setPhaseId($id);
		if(!is_null($startDate)){
			$this->setStartDate($startDate);
			$this->setEndDate($endDate);
		}
	}

	protected function setPhaseName($phaseName){

		if(is_string($phaseName)){

			switch($phaseName){
				case SelectionProcessConstants::HOMOLOGATION_PHASE:
				case SelectionProcessConstants::PRE_PROJECT_EVALUATION_PHASE:
				case SelectionProcessConstants::WRITTEN_TEST_PHASE:
				case SelectionProcessConstants::ORAL_TEST_PHASE:
					$this->phaseName = $phaseName;
					break;

				default:
					throw new SelectionProcessException(self::INVALID_PHASE_NAME);
					
			}
		}else{
			throw new SelectionProcessException(self::INVALID_PHASE_NAME);
		}
	}

	protected function setPhaseId($id){

		if($id !== FALSE){

			if(!is_nan((double) $id) && $id > 0){
				$this->id = $id;
			}else{
				show_error("O banco de dados retornou um registro com ID inválido. Contate o administrador.<br> Exceção lançada: ".self::INVALID_ID, 500, "Algo errado com o banco de dados.");
			}
		}else{
			//If the ID is FALSE, is because is a new object, not coming from DB
			$this->id = $id;
		}
	}

	private function setStartDate($startDate){
		
		if($startDate !== FALSE){
			$date = validateDate($startDate);

			if($date !== FALSE){

					$validStartDate = formatDateToDateTime($date);

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
	   	else{
	   		$this->startDate = NULL;
	   	}
	}

	private function setEndDate($endDate){
		
		if($endDate !== FALSE){
			$date = validateDate($endDate);

			if($date !== FALSE){

				$validStartDate = formatDateToDateTime($date);

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
	   	else{
	   		$this->endDate = NULL;
	   	}
	}

	public function getPhaseId(){
		return $this->id;
	}

	public function getPhaseName(){
		return $this->phaseName;
	}

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

}