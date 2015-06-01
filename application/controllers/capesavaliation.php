<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CapesAvaliation extends CI_Controller {

	public function getCapesAvaliationsNews(){
		
		$this->load->model("capesavaliation_model");
		
		$news = $this->capesavaliation_model->getAvaliationAtualizations();
		
		return $news;
		
	}
	
	public function checkAsVisualized(){
		
	}

}
