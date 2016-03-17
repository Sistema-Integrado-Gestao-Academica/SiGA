<?php
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

abstract class ProcessPhase{

	const INVALID_PHASE_NAME = "Fase inválida. Fases disponíveis: Homologação, Avaliação de Pré-Projeto, Prova escrita e Prova oral.";

	protected $phaseName;

	public function __construct($phaseName){
		$this->setPhaseName($phaseName);
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

	public function getPhaseName(){
		return $this->phaseName;
	}
}