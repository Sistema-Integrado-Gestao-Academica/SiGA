<?php
class Discipline extends CI_Controller {

	public function discipline_index(){
		$this->load->template("discipline/index_discipline");
	}
	
	public function getAllDisciplines(){
		$this->load->model('discipline_model');
		$registeredDisciplines = $this->discipline_model->listAllDisciplines();
		
		return $registeredDisciplines;
	}
	
}