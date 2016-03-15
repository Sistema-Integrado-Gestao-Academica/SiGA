<?php

abstract class ProcessPhase{

	protected $phaseName;

	public function __construct($phaseName){
		$this->setPhaseName($phaseName);
	}

	private function setPhaseName($phaseName){
		$this->phaseName = $phaseName;
	}

	public function getPhaseName(){
		return $this->phaseName;
	}
}