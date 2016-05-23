<?php

class Utils extends MX_Controller {
	
	public function migrate() {
		$this->load->library('migration');
		if ($this->migration->current()) {
			$this->load->test_template("migration_status");
		} else {
			show_error($this->migration->error_string());
		}
	}
	
	public function loadSecretaria(){
		$this->load->template('secretary/index_secretary');
	}
	
	public function loadAvaliationAreas(){
		$this->load->model("program/program_model");
		
		$lines = file(base_url("area_avaliacao.txt"));
		foreach ($lines as $lineID => $areaName){
			$this->program_model->parseProgramAreas($areaName);
		}
		
	}
	
}