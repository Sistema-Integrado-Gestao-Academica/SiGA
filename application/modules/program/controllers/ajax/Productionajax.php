<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProductionAjax extends MX_Controller {

	public function getISSNAndQualis(){

		$periodic = $this->input->post("periodic");

		$this->load->model("program/production_model");
		$qualis = $this->production_model->getQualisByPeriodicName($periodic);

		$json = array();
		if($qualis !== FALSE){

	        $json = array (
	            'qualis'=> $qualis[0]['qualis'],
	            'issn' => $qualis[0]['issn']
	        );
	        echo json_encode($json);
		}
		else{
	        echo json_encode($json);
		}

	}

	public function getPeriodicNameAndQualis(){

		$issn = $this->input->post("issn");

		$this->load->model("program/production_model");
		$qualis = $this->production_model->getQualisByISSN($issn);

		$json = array();
		if($qualis !== FALSE){

	        $json = array (
	            'qualis'=> $qualis[0]['qualis'],
	            'periodic' => $qualis[0]['periodic']
	        );
	        echo json_encode($json);
		}
		else{
	        echo json_encode($json);
		}

	}

	public function getAuthorByCpf($cpfs){
		
	}

}