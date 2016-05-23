<?php

require_once "ProcessPhase.php";
require_once(APPPATH."/constants/SelectionProcessConstants.php");
require_once(APPPATH."/exception/SelectionProcessException.php");

class HomologationOld extends ProcessPhase{
	
	public function __construct($id = FALSE){
		parent::__construct(SelectionProcessConstants::HOMOLOGATION_PHASE, $id);
	}
}