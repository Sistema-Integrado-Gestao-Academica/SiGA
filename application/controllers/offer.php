<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');

class Offer extends CI_Controller {

	public function newOffer(){

		$this->load->model('offer_model');

		$semester = new Semester();
		$currentSemester = $semester->getCurrentSemester();

		$offerData = $this->offer_model->newOffer($currentSemester['id_semester']);
		
		$offerData['disciplines'] = $this->getOfferDisciplines($offerData['id_offer']);
		
		loadTemplateSafelyByGroup('secretario', 'offer/new_offer', $offerData);		
	}

	public function addDisciplines($idOffer){
		echo "<h1>Adicionar disciplinas aqui</h1>";
	}

	public function getOfferDisciplines($idOffer){
		
		$this->load->model('offer_model');

		$disciplines = $this->offer_model->getOfferDisciplines($idOffer);

		return $disciplines;
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
