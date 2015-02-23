<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Program extends CI_Controller {
	
	public function getAllPrograms(){

		$this->load->model('program_model');

		$programs = $this->program_model->getAllPrograms();

		return $programs;
	}
}
