<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/security/session/SessionManager.php");

class CapesAvaliation extends CI_Controller {

	public function getCapesAvaliationsNews(){
		
		$this->load->model("capesavaliation_model");
		
		$news = $this->capesavaliation_model->getAvaliationAtualizations();
		
		return $news;
		
	}
	
	public function checkAsVisualized($avaliationId){
		
		$session = SessionManager::getInstance();

		$this->load->model("capesavaliation_model");
		
		$visualized = $this->capesavaliation_model->changeToVisualized($avaliationId);
		
		if($visualized){
			$insertStatus = "success";
			$insertMessage =  "Mensagem salva como visualizada sucesso";
		}else{
			$insertStatus = "danger";
			$insertMessage =  "Falha na atualização, tente novamente.";
		}
		
		$session->showFlashMessage($insertStatus, $insertMessage);
		
		redirect('login');
	}

}
