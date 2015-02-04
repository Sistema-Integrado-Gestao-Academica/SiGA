<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');

class Offer extends CI_Controller {

	public function newOffer($courseId){

		$this->load->model('offer_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offer = array(
			'semester' => $currentSemester['id_semester'],
			'course' => $courseId,
			'offer_status' => "proposed"
		);

		$wasSaved = $this->offer_model->newOffer($offer);

		if($wasSaved){
			$status = "success";
			$message = "Lista de oferta criada com sucesso. Adicione disciplinas em EDITAR.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar a lista de oferta. Tente novamente.";
		}
		
		$this->session->set_flashdata($status, $message);	
		redirect('usuario/secretary_offerList');
	}

	public function addDisciplines($idOffer){
		echo "<h1>Adicionar disciplinas aqui</h1>";
	}

	public function getOfferDisciplines($idOffer){
		
		$this->load->model('offer_model');

		$disciplines = $this->offer_model->getOfferDisciplines($idOffer);

		return $disciplines;
	}

	public function getCourseOfferList($courseId, $semester){
		
		$this->load->model('offer_model');

		$offerLists = $this->offer_model->getCourseOfferList($courseId, $semester);

		return $offerLists;
	}

	public function getProposedOfferLists(){
		$this->load->model('offer_model');

		$offerLists = $this->offer_model->getProposedOfferLists();

		return $offerLists;
	}

	public function getOfferSemester($offerId){
		$this->load->model('offer_model');

		$offerSemester = $this->offer_model->getOfferSemester($offerId);

		return $offerSemester;
	}

	private function createNewOffer(){
		$this->load->model('offer_model');

	}
}
