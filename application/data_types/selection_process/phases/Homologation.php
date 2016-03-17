<?php

require_once "ProcessPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class Homologation extends ProcessPhase{
	
	const NOT_HOMOLOGATION_PHASE = "Para a fase de Homologação o nome da fase deve ser 'Homologação'."; 

	public function __construct($phaseName){
		parent::__construct($phaseName);
	}

	protected function setPhaseName($phaseName){

		if(is_string($phaseName)){
			if($phaseName === SelectionProcessConstants::HOMOLOGATION_PHASE){
				$this->phaseName = $phaseName;
			}else{
				throw new SelectionProcessException(self::NOT_HOMOLOGATION_PHASE);
			}
		}else{
			throw new SelectionProcessException(self::NOT_HOMOLOGATION_PHASE);
		}
	}
}