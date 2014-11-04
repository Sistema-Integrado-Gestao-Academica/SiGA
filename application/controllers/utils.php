<?php

class Utils extends CI_Controller {
	
	public function migrate() {
		$this->load->library('migration');
		$sucess = $this->migration->current();
		if ($sucess) {
			echo "Migrado com sucesso";
		} else {
			show_error($this->migration->error_string());
		}
	}
}