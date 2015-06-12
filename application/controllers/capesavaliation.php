<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CapesAvaliation extends CI_Controller {

	public function getCapesAvaliationsNews(){
		
		$this->load->model("capesavaliation_model");
		
		$news = $this->capesavaliation_model->getAvaliationAtualizations();
		
		return $news;
		
	}
	
	public function checkAsVisualized($avaliationId){
		$this->load->model("capesavaliation_model");
		
		$visualized = $this->capesavaliation_model->changeToVisualized($avaliationId);
		
		if($visualized){
			$insertStatus = "success";
			$insertMessage =  "Mensagem salva como visualizada sucesso";
		}else{
			$insertStatus = "danger";
			$insertMessage =  "Falha na atualização, tente novamente.";
		}
		
		$this->session->set_flashdata($insertStatus, $insertMessage);
		
		redirect('login');
	}

}
