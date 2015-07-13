<?php

class Utils extends CI_Controller {
	
	public function migrate() {
		$this->load->library('migration');
		if ($this->migration->current()) {
			echo "Migrado com sucesso";
		} else {
			show_error($this->migration->error_string());
		}
	}
	
	public function loadSecretaria(){
		$this->load->template('secretary/index_secretary');
	}
	
	public function loadCapesAvaliationAreas(){
		$this->load->model("capesavaliation_model");
		
		$lines = file(base_url("area_avaliacao.txt"));
		foreach ($lines as $lineID => $areaName){
			$saved = $this->capesavaliation_model->saveNewAvaliationArea($areaName);
			if ($saved){
				echo "Área : " . $areaName . " salva com sucesso! <br>";
			}
		}
		
	}
	
	
}