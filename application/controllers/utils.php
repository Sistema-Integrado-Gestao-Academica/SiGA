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
}