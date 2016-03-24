<?php
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

abstract class ProcessPhase{

	const INVALID_PHASE_NAME = "Fase inválida. Fases disponíveis: Homologação, Avaliação de Pré-Projeto, Prova escrita e Prova oral.";
	const INVALID_ID = "O ID da fase deve ser um número maior que zero.";

	protected $id;
	protected $phaseName;

	public function __construct($phaseName, $id = FALSE){
		$this->setPhaseName($phaseName);
		$this->setPhaseId($id);
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

	public function getPhaseId(){
		return $this->id;
	}

	public function getPhaseName(){
		return $this->phaseName;
	}
}